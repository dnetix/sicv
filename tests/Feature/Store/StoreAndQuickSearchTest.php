<?php

namespace Tests\Feature\Store;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\ItemType;
use App\Models\Sale;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAndQuickSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_store_item_can_be_created_with_explicit_prices(): void
    {
        ItemType::ensure(1, 'Sin definir');

        $this->actingAs($this->user)->post(route('store.store'), [
            'description' => 'Bicicleta todo terreno',
            'item_type_id' => 1,
            'cost' => 80_000,
            'price' => 150_000,
            'stock' => 1,
        ])->assertRedirect(route('store.create'));

        $item = StoreItem::query()->sole();
        $this->assertSame(80_000, $item->cost);
        $this->assertNull($item->contract_id);
    }

    public function test_store_item_cost_is_required(): void
    {
        ItemType::ensure(1, 'Sin definir');

        $this->actingAs($this->user)->post(route('store.store'), [
            'description' => 'Sin costo',
            'item_type_id' => 1,
            'price' => 150_000,
            'stock' => 1,
        ])->assertSessionHasErrors('cost');
    }

    public function test_expense_is_stamped_with_server_time_and_user(): void
    {
        $type = ExpenseType::factory()->create();

        $this->actingAs($this->user)->post(route('expenses.store'), [
            'amount' => 25_000,
            'expense_type_id' => $type->id,
            'description' => 'Papelería',
        ])->assertRedirect(route('expenses.index'));

        $expense = Expense::query()->sole();
        $this->assertSame($this->user->id, $expense->user_id);
        $this->assertTrue($expense->spent_at->isToday());
    }

    public function test_quick_search_dispatches_by_prefix(): void
    {
        $client = Client::factory()->create(['document_number' => '43512345']);
        $contract = Contract::factory()->for($client)->create();
        $sale = Sale::query()->create([
            'client_id' => $client->id,
            'sold_at' => today(),
            'total' => 1000,
            'warranty_days' => 0,
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('quick-search', ['q' => "NC{$sale->id}"]))
            ->assertRedirect(route('sales.show', $sale));

        $this->actingAs($this->user)
            ->get(route('quick-search', ['q' => 'CL43512345']))
            ->assertRedirect(route('clients.show', $client));

        $this->actingAs($this->user)
            ->get(route('quick-search', ['q' => (string) $contract->id]))
            ->assertRedirect(route('contracts.show', $contract));

        $this->actingAs($this->user)
            ->get(route('quick-search', ['q' => 'NC99999999']))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }
}
