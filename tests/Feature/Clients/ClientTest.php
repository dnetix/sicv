<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_clients_pages_require_authentication(): void
    {
        $this->get(route('clients.index'))->assertRedirect(route('login'));
        $this->get(route('clients.search', ['q' => 'x']))->assertRedirect(route('login'));
    }

    public function test_client_can_be_created(): void
    {
        $response = $this->actingAs($this->user)->post(route('clients.store'), [
            'document_number' => '1036612345',
            'document_type' => 'CC',
            'name' => 'PEDRO PEREZ',
            'document_issue_place' => 'La Ceja',
            'city' => 'La Ceja',
            'phone' => '5531234',
        ]);

        $client = Client::query()->where('document_number', '1036612345')->first();

        $this->assertNotNull($client);
        $response->assertRedirect(route('clients.show', $client));
    }

    public function test_document_number_must_be_unique_and_without_dots(): void
    {
        Client::factory()->create(['document_number' => '123456789']);

        $base = [
            'document_type' => 'CC',
            'name' => 'PEDRO PEREZ',
            'document_issue_place' => 'La Ceja',
            'city' => 'La Ceja',
        ];

        $this->actingAs($this->user)
            ->post(route('clients.store'), $base + ['document_number' => '123456789'])
            ->assertSessionHasErrors('document_number');

        $this->actingAs($this->user)
            ->post(route('clients.store'), $base + ['document_number' => '1.234.567'])
            ->assertSessionHasErrors('document_number');
    }

    public function test_search_matches_document_prefix_and_name_tokens(): void
    {
        Client::factory()->create(['document_number' => '98765', 'name' => 'MARIA LOPEZ GOMEZ']);
        Client::factory()->create(['document_number' => '12398', 'name' => 'JUAN LOPEZ RUIZ']);

        // Every token must match (name contains / document prefix), like the legacy search.
        $this->actingAs($this->user)
            ->getJson(route('clients.search', ['q' => 'lopez maria']))
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'MARIA LOPEZ GOMEZ']);

        $this->actingAs($this->user)
            ->getJson(route('clients.search', ['q' => '987']))
            ->assertJsonCount(1)
            ->assertJsonFragment(['document_number' => '98765']);

        // Document match is prefix-only.
        $this->actingAs($this->user)
            ->getJson(route('clients.search', ['q' => '765']))
            ->assertJsonCount(0);
    }

    public function test_client_can_be_updated_but_document_is_immutable(): void
    {
        $client = Client::factory()->create(['document_number' => '555111', 'name' => 'ORIGINAL']);

        $this->actingAs($this->user)->put(route('clients.update', $client), [
            'document_number' => '999999',
            'document_type' => 'CC',
            'name' => 'NUEVO NOMBRE',
            'document_issue_place' => 'Rionegro',
            'city' => 'Rionegro',
        ])->assertRedirect(route('clients.show', $client));

        $client->refresh();
        $this->assertSame('NUEVO NOMBRE', $client->name);
        $this->assertSame('555111', $client->document_number);
    }

    public function test_show_page_displays_contract_history(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($this->user)
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($client->name)
            ->assertSee('Sin contratos registrados.');
    }

    public function test_cities_endpoint_returns_distinct_issue_places(): void
    {
        Client::factory()->count(2)->create(['document_issue_place' => 'Medellin']);
        Client::factory()->create(['document_issue_place' => 'La Ceja']);

        $this->actingAs($this->user)
            ->getJson(route('clients.cities', ['q' => 'mede']))
            ->assertJson(['Medellin']);
    }
}
