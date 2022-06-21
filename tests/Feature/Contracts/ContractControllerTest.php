<?php

namespace Tests\Feature\Contracts;

use App\Helpers\RepositoryHelper;
use App\Models\Clients\Client;
use App\Models\Contracts\Contract;
use Database\Seeders\ArticleTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = ArticleTypesTableSeeder::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
    }

    private function basicRequest(array $overrides = []): array
    {
        $client = Client::factory()->create();

        return [
            'description' => [
                'Cadena Oro 18K',
                'Bicicleta Todoterreno',
            ],
            'article_type_id' => [
                3,
                '6',
            ],
            'weight' => [
                2.6,
                null,
            ],
            'amount' => [
                '250,000',
                120000,
            ],
            'client_id' => $client->id(),
            'months' => '5',
            'percentage' => '9',
            'note' => 'Testing some shit',
            'importance' => 'info',
        ];
    }

    public function test_does_not_respond_without_authentication(): void
    {
        $this->app['auth']->logout();

        $response = $this->json('POST', route('contract.store'), $this->basicRequest());

        $response->assertStatus(401);
    }

    public function test_handles_an_empty_call(): void
    {
        $response = $this->json('POST', route('contract.store'), [
            'foo' => null,
        ]);
        $response->assertStatus(422);
    }

    public function test_it_successfully_stores_a_contract(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->json('POST', route('contract.store'), $this->basicRequest());

        /**
         * @var Contract $contract
         */
        $contract = RepositoryHelper::forContracts()->getLastContracts(1)->first();

        $this->assertEquals(370000, $contract->amount());
        $this->assertTrue($contract->isActive());
        $this->assertEquals(9, $contract->percentage());
        $this->assertEquals(5, $contract->months());

        $articles = RepositoryHelper::forContracts()->getContractArticles($contract);
        foreach ($articles as $index => $article) {
            $this->assertEquals(str_replace(',', '', $this->basicRequest()['amount'][$index]), $article->articleAmount());
            $this->assertEquals($this->basicRequest()['description'][$index], $article->description());
            $this->assertEquals($this->basicRequest()['weight'][$index], $article->weight());
        }

        $response->assertRedirect(route('contract.print', $contract->id()));
    }
}
