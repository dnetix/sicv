<?php

namespace Tests\Feature\Contracts;

use App\Enums\ContractStatus;
use App\Models\AmountOverride;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Contract;
use App\Models\ItemType;
use App\Models\RepossessionEntry;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_contract_can_be_created_and_redirects_to_print(): void
    {
        $client = Client::factory()->create();
        ItemType::ensure(1, 'Sin definir');

        $response = $this->actingAs($this->user)->post(route('contracts.store'), [
            'client_id' => $client->id,
            'description' => 'Televisor LG 42 pulgadas',
            'item_type_id' => 1,
            'amount' => 200_000,
            'monthly_rate' => 10,
            'term_months' => 4,
        ]);

        $contract = Contract::query()->latest('id')->first();

        $this->assertNotNull($contract);
        $this->assertSame(ContractStatus::Active, $contract->status);
        $response->assertRedirect(route('contracts.print', $contract));
    }

    public function test_gold_contracts_require_weight_server_side(): void
    {
        $client = Client::factory()->create();
        ItemType::ensure(ItemType::GOLD, 'Oro');

        $this->actingAs($this->user)->post(route('contracts.store'), [
            'client_id' => $client->id,
            'description' => 'Anillo de oro',
            'item_type_id' => ItemType::GOLD,
            'amount' => 200_000,
            'monthly_rate' => 10,
            'term_months' => 4,
        ])->assertSessionHasErrors('weight_grams');
    }

    public function test_extension_payment_buys_fractional_months(): void
    {
        $contract = Contract::factory()->create(['amount' => 1_000_000, 'monthly_rate' => 10]);

        $this->actingAs($this->user)
            ->post(route('contracts.extend', $contract), ['amount' => 50_000])
            ->assertRedirect(route('contracts.show', $contract));

        $extension = $contract->extensions()->first();
        $this->assertSame(50_000, $extension->amount);
        $this->assertSame(0.5, $extension->months);
    }

    public function test_extension_is_rejected_for_inactive_contract_and_zero_amount(): void
    {
        $contract = Contract::factory()->create(['status' => ContractStatus::Redeemed]);

        $this->actingAs($this->user)
            ->post(route('contracts.extend', $contract), ['amount' => 10_000])
            ->assertSessionHasErrors('amount');

        $active = Contract::factory()->create();

        $this->actingAs($this->user)
            ->post(route('contracts.extend', $active), ['amount' => 0])
            ->assertSessionHasErrors('amount');
    }

    public function test_redeem_stores_collected_amount_and_audits_overrides(): void
    {
        $contract = Contract::factory()->create([
            'amount' => 1_000_000,
            'monthly_rate' => 10,
            'started_at' => now()->startOfDay(),
        ]);

        // Payoff today = 1 month interest + capital = 1.100.000; operator collects less.
        $this->actingAs($this->user)
            ->post(route('contracts.redeem', $contract), ['amount' => 1_050_000]);

        $contract->refresh();
        $this->assertSame(ContractStatus::Redeemed, $contract->status);
        $this->assertSame(1_050_000, $contract->settled_amount);
        $this->assertNotNull($contract->ended_at);

        $override = AmountOverride::query()->sole();
        $this->assertSame('redeem', $override->operation);
        $this->assertSame(1_100_000, $override->computed_amount);
        $this->assertSame(1_050_000, $override->entered_amount);
        $this->assertSame($this->user->id, $override->user_id);
    }

    public function test_redeem_with_suggested_amount_records_no_override(): void
    {
        $contract = Contract::factory()->create([
            'amount' => 1_000_000,
            'monthly_rate' => 10,
            'started_at' => now()->startOfDay(),
        ]);

        $this->actingAs($this->user)
            ->post(route('contracts.redeem', $contract), ['amount' => 1_100_000]);

        $this->assertSame(0, AmountOverride::query()->count());
    }

    public function test_void_keeps_original_amount_and_zeroes_contract(): void
    {
        $contract = Contract::factory()->create(['amount' => 300_000]);

        $this->actingAs($this->user)
            ->post(route('contracts.void', $contract), ['reason' => 'Error de digitación']);

        $contract->refresh();
        $this->assertSame(ContractStatus::Voided, $contract->status);
        $this->assertSame(0, $contract->amount);
        $this->assertSame('Error de digitación', $contract->void->reason);
        $this->assertSame(300_000, $contract->void->original_amount);
    }

    public function test_forfeit_creates_store_item_with_loan_as_cost(): void
    {
        $contract = Contract::factory()->create([
            'amount' => 500_000,
            'monthly_rate' => 10,
            'started_at' => now()->startOfDay(),
        ]);

        $this->actingAs($this->user)
            ->post(route('contracts.forfeit', $contract), ['price' => 700_000]);

        $contract->refresh();
        $this->assertSame(ContractStatus::InStore, $contract->status);
        $this->assertSame(0, $contract->settled_amount);

        $item = StoreItem::query()->sole();
        $this->assertSame($contract->id, $item->contract_id);
        $this->assertSame(500_000, $item->cost);
        $this->assertSame(700_000, $item->price);
        $this->assertSame(1, $item->stock);
        $this->assertSame($contract->description, $item->description);
    }

    public function test_operations_require_active_status(): void
    {
        $contract = Contract::factory()->create(['status' => ContractStatus::Sold]);

        $this->actingAs($this->user)
            ->post(route('contracts.redeem', $contract), ['amount' => 1000])
            ->assertSessionHasErrors();

        $this->actingAs($this->user)
            ->post(route('contracts.void', $contract), ['reason' => 'motivo x'])
            ->assertSessionHasErrors();

        $this->actingAs($this->user)
            ->post(route('contracts.forfeit', $contract), ['price' => 1000])
            ->assertSessionHasErrors();

        $this->assertSame(ContractStatus::Sold, $contract->fresh()->status);
    }

    public function test_queued_contract_can_be_removed_from_queue(): void
    {
        $contract = Contract::factory()->create();
        RepossessionEntry::query()->create([
            'contract_id' => $contract->id,
            'queued_at' => now(),
            'user_id' => $this->user->id,
        ]);

        $this->assertTrue($contract->isQueued());

        $this->actingAs($this->user)
            ->delete(route('contracts.queue.remove', $contract))
            ->assertRedirect(route('contracts.show', $contract));

        $this->assertFalse($contract->fresh()->isQueued());
    }

    public function test_print_page_renders_with_barcode_and_copy_watermark(): void
    {
        CompanySetting::query()->create([
            'legal_name' => 'Compraventa El Diamante S.A.S.',
            'tax_id' => '900123456-1',
            'name' => 'Compraventa El Diamante',
            'address' => 'Calle 1 # 2-3',
            'phone' => '5551234',
            'city' => 'La Ceja',
        ]);

        $contract = Contract::factory()->create();

        $this->actingAs($this->user)
            ->get(route('contracts.print', $contract))
            ->assertOk()
            ->assertSee('Contrato de Compraventa')
            ->assertSee('pacto de retroventa')
            ->assertDontSee('DUPLICADO');

        $this->actingAs($this->user)
            ->get(route('contracts.print', [$contract, 'copy' => 1]))
            ->assertOk()
            ->assertSee('DUPLICADO');
    }
}
