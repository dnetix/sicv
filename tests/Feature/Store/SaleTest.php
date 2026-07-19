<?php

namespace Tests\Feature\Store;

use App\Enums\ContractStatus;
use App\Models\AmountOverride;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Contract;
use App\Models\Sale;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->client = Client::factory()->create();
    }

    public function test_checkout_creates_sale_decrements_stock_and_flips_contract(): void
    {
        $contract = Contract::factory()->create(['status' => ContractStatus::InStore, 'ended_at' => now()->subDay()]);
        $fromContract = StoreItem::factory()->create(['contract_id' => $contract->id, 'price' => 300_000]);
        $direct = StoreItem::factory()->create(['price' => 100_000, 'stock' => 2]);

        $originalEndedAt = $contract->ended_at;

        $this->actingAs($this->user)->post(route('sales.store'), [
            'client_id' => $this->client->id,
            'warranty_days' => 30,
            'items' => [
                ['store_item_id' => $fromContract->id, 'price' => 300_000],
                ['store_item_id' => $direct->id, 'price' => 90_000],
            ],
        ])->assertRedirect();

        $sale = Sale::query()->sole();
        $this->assertSame(390_000, $sale->total);
        $this->assertSame(30, $sale->warranty_days);
        $this->assertSame(2, $sale->items()->count());

        $this->assertSame(0, $fromContract->fresh()->stock);
        $this->assertSame(1, $direct->fresh()->stock);

        // Contract flips to Sold, keeping its original exit date (legacy setVendido).
        $contract->refresh();
        $this->assertSame(ContractStatus::Sold, $contract->status);
        $this->assertTrue($contract->ended_at->equalTo($originalEndedAt));

        // Only the discounted line generates an override audit.
        $override = AmountOverride::query()->sole();
        $this->assertSame('sale_line', $override->operation);
        $this->assertSame(100_000, $override->computed_amount);
        $this->assertSame(90_000, $override->entered_amount);
    }

    public function test_checkout_rejects_out_of_stock_and_duplicate_items(): void
    {
        $soldOut = StoreItem::factory()->soldOut()->create();

        $this->actingAs($this->user)->post(route('sales.store'), [
            'client_id' => $this->client->id,
            'warranty_days' => 0,
            'items' => [['store_item_id' => $soldOut->id, 'price' => 1000]],
        ])->assertSessionHasErrors('items');

        $item = StoreItem::factory()->create();

        $this->actingAs($this->user)->post(route('sales.store'), [
            'client_id' => $this->client->id,
            'warranty_days' => 0,
            'items' => [
                ['store_item_id' => $item->id, 'price' => 1000],
                ['store_item_id' => $item->id, 'price' => 1000],
            ],
        ])->assertSessionHasErrors('items');

        $this->assertSame(0, Sale::query()->count());
        $this->assertSame(1, $item->fresh()->stock);
    }

    public function test_checkout_requires_a_registered_client(): void
    {
        $item = StoreItem::factory()->create();

        $this->actingAs($this->user)->post(route('sales.store'), [
            'warranty_days' => 0,
            'items' => [['store_item_id' => $item->id, 'price' => 1000]],
        ])->assertSessionHasErrors('client_id');
    }

    public function test_item_search_matches_description_id_and_contract_prefix(): void
    {
        $contract = Contract::factory()->create(['status' => ContractStatus::InStore]);
        StoreItem::factory()->create(['contract_id' => $contract->id, 'description' => 'Televisor Samsung']);
        StoreItem::factory()->create(['description' => 'Licuadora Oster']);
        StoreItem::factory()->soldOut()->create(['description' => 'Televisor LG agotado']);

        $this->actingAs($this->user)
            ->getJson(route('sales.search-items', ['q' => 'televisor']))
            ->assertJsonCount(1)
            ->assertJsonFragment(['description' => 'Televisor Samsung']);

        $this->actingAs($this->user)
            ->getJson(route('sales.search-items', ['q' => (string) $contract->id]))
            ->assertJsonFragment(['contract_id' => $contract->id]);
    }

    public function test_receipt_print_renders_warranty_wording(): void
    {
        CompanySetting::query()->create([
            'legal_name' => 'X S.A.S.', 'tax_id' => '1', 'name' => 'X',
            'address' => 'Calle 1', 'phone' => '1', 'city' => 'La Ceja',
        ]);

        $item = StoreItem::factory()->create(['price' => 50_000]);

        $this->actingAs($this->user)->post(route('sales.store'), [
            'client_id' => $this->client->id,
            'warranty_days' => 0,
            'items' => [['store_item_id' => $item->id, 'price' => 50_000]],
        ]);

        $sale = Sale::query()->sole();

        $this->actingAs($this->user)
            ->get(route('sales.print', $sale))
            ->assertOk()
            ->assertSee('NO tiene garantía')
            ->assertSee('Ley 1480 de 2011');
    }
}
