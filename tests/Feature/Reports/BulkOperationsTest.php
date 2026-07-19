<?php

namespace Tests\Feature\Reports;

use App\Enums\ContractStatus;
use App\Models\AmountOverride;
use App\Models\Contract;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkOperationsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_expired_contracts_can_be_queued_skipping_duplicates(): void
    {
        $contracts = Contract::factory()->count(2)->startedMonthsAgo(6)->create();

        $this->actingAs($this->user)->post(route('operations.queue'), [
            'contracts' => $contracts->pluck('id')->all(),
        ])->assertRedirect(route('reports.queued'));

        $this->assertTrue($contracts[0]->fresh()->isQueued());
        $this->assertTrue($contracts[1]->fresh()->isQueued());

        // Re-queueing the same contracts is a no-op, not an error.
        $this->actingAs($this->user)->post(route('operations.queue'), [
            'contracts' => $contracts->pluck('id')->all(),
        ])->assertRedirect(route('reports.queued'))->assertSessionHas('status');
    }

    public function test_bulk_pull_forfeits_items_and_scraps_gold(): void
    {
        $tv = Contract::factory()->startedMonthsAgo(6)->create(['amount' => 200_000]);
        $gold = Contract::factory()->gold()->startedMonthsAgo(6)->create(['amount' => 500_000]);

        $this->actingAs($this->user)->post(route('operations.queue'), [
            'contracts' => [$tv->id, $gold->id],
        ]);

        $this->actingAs($this->user)->post(route('operations.pull'), [
            'contracts' => [$tv->id, $gold->id],
            'prices' => [$tv->id => 250_000, $gold->id => 500_000],
            'scrap_gold' => 1,
        ])->assertRedirect(route('reports.queued'));

        $tv->refresh();
        $gold->refresh();

        $this->assertSame(ContractStatus::InStore, $tv->status);
        $this->assertSame(ContractStatus::Scrapped, $gold->status);

        // Only the non-gold contract produced a store item.
        $item = StoreItem::query()->sole();
        $this->assertSame($tv->id, $item->contract_id);
        $this->assertSame(200_000, $item->cost);
        $this->assertSame(250_000, $item->price);

        // Queue rows cleaned up on both paths.
        $this->assertFalse($tv->isQueued());
        $this->assertFalse($gold->isQueued());

        // Price differed from the suggested loan amount => audited.
        $override = AmountOverride::query()->sole();
        $this->assertSame('forfeit', $override->operation);
        $this->assertSame(200_000, $override->computed_amount);
        $this->assertSame(250_000, $override->entered_amount);
    }

    public function test_bulk_pull_without_scrapping_moves_gold_to_store(): void
    {
        $gold = Contract::factory()->gold()->startedMonthsAgo(6)->create(['amount' => 500_000]);

        $this->actingAs($this->user)->post(route('operations.pull'), [
            'contracts' => [$gold->id],
            'prices' => [$gold->id => 500_000],
            'scrap_gold' => 0,
        ]);

        $this->assertSame(ContractStatus::InStore, $gold->fresh()->status);
        $this->assertSame(1, StoreItem::query()->count());
    }

    public function test_contracts_can_be_bulk_unqueued(): void
    {
        $contract = Contract::factory()->startedMonthsAgo(6)->create();

        $this->actingAs($this->user)->post(route('operations.queue'), ['contracts' => [$contract->id]]);
        $this->assertTrue($contract->fresh()->isQueued());

        $this->actingAs($this->user)->post(route('operations.unqueue'), ['contracts' => [$contract->id]])
            ->assertRedirect(route('reports.queued'));

        $this->assertFalse($contract->fresh()->isQueued());
        $this->assertSame(ContractStatus::Active, $contract->fresh()->status);
    }
}
