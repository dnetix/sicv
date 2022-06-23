<?php

namespace Tests\Feature\Clients;

use App\Helpers\RepositoryHelper;
use App\Models\Clients\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
    }

    public function test_does_not_respond_without_authentication(): void
    {
        $this->app['auth']->logout();

        $response = $this->json('POST', route('api.client.search'), [
            'terms' => 'diego',
        ]);
        $response->assertStatus(401);
    }

    public function test_handles_an_empty_call(): void
    {
        $response = $this->json('POST', route('api.client.search'), [
            'terms' => null,
        ]);
        $response->assertStatus(422);
    }

    public function test_it_returns_empty_on_not_found_search_clients(): void
    {
        $response = $this->json('POST', route('api.client.search'), [
            'terms' => 'notfound',
        ]);
        $response->assertStatus(200);
        // Asserting that is an array instead of an object
        $this->assertEquals('[', substr($response->content(), 0, 1));
        $this->assertEmpty($response->json());
    }

    public function test_it_returns_an_array_when_clients_found(): void
    {
        Client::factory()->create([
            'document' => '1040035000',
            'name' => 'Diego Calle',
        ]);

        Client::factory()->create([
            'document' => '1040036000',
            'name' => 'Another',
        ]);

        Client::factory()->create([
            'document' => '1040036001',
            'name' => 'Diego Marin',
        ]);

        $response = $this->json('POST', route('api.client.search'), [
            'terms' => 'Diego',
        ]);

        $data = $response->json();
        $this->assertEquals(2, count($data));
        // Asserting that is an array instead of an object
        $this->assertEquals('[', substr($response->content(), 0, 1));
    }

    public function test_it_stores_a_client_correctly()
    {
        $response = $this->json('POST', route('client.store'), [
            'name' => 'Diego Calle',
            'email' => 'dnetix@yopmail.com',
            'document_type' => 'CC',
            'document' => '1040035000',
            'expedition_city' => 'Medellin',
            'mobile' => '3006108300',
        ]);
        $response->assertCreated();
        $this->assertNotEmpty($response['id']);

        $client = RepositoryHelper::forClients()->getClientByDocument('1040035000');

        $this->assertEquals('Diego Calle', $client->name());
        $this->assertEquals('dnetix@yopmail.com', $client->email());
        $this->assertEquals('CC', $client->documentType());
        $this->assertEquals('1040035000', $client->document());
        $this->assertEquals('3006108300', $client->mobile());
    }
}
