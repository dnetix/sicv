<?php

namespace Tests\Feature\Clients;

use App\Models\Clients\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_empty_on_not_found_search_clients()
    {
        $response = $this->post(route('api.client.search'), [
            'terms' => 'notfound',
        ]);
        $response->assertStatus(200);
        $this->assertEmpty($response->json());
    }

    public function test_it_returns_an_array_when_clients_found()
    {
        Client::factory()->create([
            'id_number' => '1040035000',
            'name' => 'Diego Calle',
        ]);

        Client::factory()->create([
            'id_number' => '1040036000',
            'name' => 'Another',
        ]);

        Client::factory()->create([
            'id_number' => '1040036001',
            'name' => 'Diego Marin',
        ]);

        $response = $this->post(route('api.client.search'), [
            'terms' => 'Diego',
        ]);

        $data = $response->json();
        $this->assertEquals(2, count($data));
        foreach ($data as $id => $client) {
            $this->assertEquals($id, $client['id']);
        }
    }
}
