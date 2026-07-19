<?php

namespace Tests\Feature\Clients;

use App\Enums\ClientNoteSeverity;
use App\Enums\UserRole;
use App\Models\Client;
use App\Models\ClientNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientNoteTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::factory()->create(['role' => UserRole::Employee]);
        $this->client = Client::factory()->create();
    }

    public function test_operator_can_add_a_note(): void
    {
        $this->actingAs($this->employee)
            ->post(route('clients.notes.store', $this->client), [
                'body' => 'Dejó su cédula física — guardada en la caja fuerte',
                'severity' => 'warning',
            ])
            ->assertRedirect();

        $note = $this->client->notes()->first();

        $this->assertNotNull($note);
        $this->assertSame(ClientNoteSeverity::Warning, $note->severity);
        $this->assertSame($this->employee->id, $note->user_id);
    }

    public function test_note_body_and_severity_are_validated(): void
    {
        $this->actingAs($this->employee)
            ->post(route('clients.notes.store', $this->client), ['body' => 'ab', 'severity' => 'bogus'])
            ->assertSessionHasErrors(['body', 'severity']);
    }

    public function test_notes_are_shown_on_the_client_page(): void
    {
        ClientNote::factory()->alert()->for($this->client)->create([
            'body' => 'No canceló el contrato #3120',
            'user_id' => $this->employee->id,
        ]);

        $this->actingAs($this->employee)
            ->get(route('clients.show', $this->client))
            ->assertOk()
            ->assertSee('No canceló el contrato #3120')
            ->assertSee($this->employee->name);
    }

    public function test_only_administrators_can_delete_notes(): void
    {
        $note = ClientNote::factory()->for($this->client)->create(['user_id' => $this->employee->id]);

        $this->actingAs($this->employee)
            ->delete(route('clients.notes.destroy', [$this->client, $note]))
            ->assertForbidden();

        $admin = User::factory()->create(['role' => UserRole::Administrator]);

        $this->actingAs($admin)
            ->delete(route('clients.notes.destroy', [$this->client, $note]))
            ->assertRedirect();

        $this->assertDatabaseMissing('client_notes', ['id' => $note->id]);
    }

    public function test_note_cannot_be_deleted_through_another_client(): void
    {
        $note = ClientNote::factory()->for($this->client)->create(['user_id' => $this->employee->id]);
        $other = Client::factory()->create();
        $admin = User::factory()->create(['role' => UserRole::Administrator]);

        $this->actingAs($admin)
            ->delete(route('clients.notes.destroy', [$other, $note]))
            ->assertNotFound();

        $this->assertDatabaseHas('client_notes', ['id' => $note->id]);
    }

    public function test_search_payload_includes_notes_for_the_contract_screen(): void
    {
        ClientNote::factory()->for($this->client)->create([
            'body' => 'Cliente de confianza',
            'user_id' => $this->employee->id,
        ]);

        $this->actingAs($this->employee)
            ->getJson(route('clients.search', ['q' => $this->client->document_number]))
            ->assertOk()
            ->assertJsonPath('0.id', $this->client->id)
            ->assertJsonPath('0.notes.0.body', 'Cliente de confianza')
            ->assertJsonPath('0.notes.0.severity', 'warning')
            ->assertJsonPath('0.notes.0.author', $this->employee->name);
    }

    public function test_contract_create_prefill_includes_notes(): void
    {
        ClientNote::factory()->alert()->for($this->client)->create([
            'body' => 'No pagó el contrato anterior',
            'user_id' => $this->employee->id,
        ]);

        $this->actingAs($this->employee)
            ->get(route('contracts.create', ['client' => $this->client->id]))
            ->assertOk()
            ->assertSee('No pagó el contrato anterior');
    }
}
