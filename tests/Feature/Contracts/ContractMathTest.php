<?php

namespace Tests\Feature\Contracts;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\ContractExtension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * These tests pin the EXACT legacy formulas. If one fails after a change,
 * the change altered business behavior the migration promised to preserve.
 */
class ContractMathTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_monthly_interest_is_floored(): void
    {
        $contract = Contract::factory()->make(['amount' => 1_000_000, 'monthly_rate' => 10]);
        $this->assertSame(100_000, $contract->monthlyInterest());

        // floor(999 * 0.05) = floor(49.95) = 49
        $contract = Contract::factory()->make(['amount' => 999, 'monthly_rate' => 5]);
        $this->assertSame(49, $contract->monthlyInterest());
    }

    public function test_months_elapsed_counts_started_months_with_plus_one(): void
    {
        Carbon::setTestNow('2026-07-18 10:00:00');

        // Same-day contract: already owes one month.
        $contract = Contract::factory()->create(['started_at' => '2026-07-18 09:00:00']);
        $this->assertSame(1, $contract->monthsElapsed());

        // 6 calendar months + 3 days => 6 full months + 1 = 7.
        $contract = Contract::factory()->create(['started_at' => '2026-01-15 14:30:00']);
        $this->assertSame(7, $contract->monthsElapsed());

        // Legacy quirk preserved: the diff runs against today at MIDNIGHT, so
        // a contract started exactly one month ago but late in the day has
        // not yet completed the calendar month (29d + hours) => 1, not 2.
        $contract = Contract::factory()->create(['started_at' => '2026-06-18 17:00:00']);
        $this->assertSame(1, $contract->monthsElapsed());

        // Started at midnight one month ago: exactly 1 month => 2.
        $contract = Contract::factory()->create(['started_at' => '2026-06-18 00:00:00']);
        $this->assertSame(2, $contract->monthsElapsed());
    }

    public function test_months_elapsed_for_closed_contract_uses_exit_date(): void
    {
        $contract = Contract::factory()->create([
            'status' => ContractStatus::Redeemed,
            'started_at' => '2026-01-10 08:00:00',
            'ended_at' => '2026-03-25 16:00:00',
        ]);

        // Jan 10 08:00 -> Mar 25 16:00 = 2 months 15 days => 2 + 1 = 3.
        $this->assertSame(3, $contract->monthsElapsed());
    }

    public function test_extension_months_are_fractional_and_unrounded(): void
    {
        $contract = Contract::factory()->create(['amount' => 1_000_000, 'monthly_rate' => 10]);
        ContractExtension::factory()->for($contract)->create(['amount' => 50_000, 'months' => 0.5]);
        ContractExtension::factory()->for($contract)->create(['amount' => 125_000, 'months' => 1.25]);

        $this->assertSame(1.75, $contract->fresh()->extendedMonths());
    }

    public function test_payoff_amount_formula(): void
    {
        Carbon::setTestNow('2026-07-18 10:00:00');

        // Started 5 months ago at midnight => monthsElapsed = 6.
        $contract = Contract::factory()->create([
            'amount' => 1_000_000,
            'monthly_rate' => 10,
            'started_at' => '2026-02-18 00:00:00',
        ]);
        ContractExtension::factory()->for($contract)->create(['months' => 2.5]);
        $contract->load('extensions');

        // (6 - 2.5) * 100.000 + 1.000.000 = 1.350.000
        $this->assertSame(1_350_000.0, $contract->payoffAmount());
    }

    public function test_due_date_floors_fractional_extension_months_and_overflows_like_strtotime(): void
    {
        $contract = Contract::factory()->create([
            'term_months' => 4,
            'started_at' => '2026-01-15 10:00:00',
        ]);
        ContractExtension::factory()->for($contract)->create(['months' => 1.9]);
        $contract->load('extensions');

        // floor(4 + 1.9) = 5 months => 2026-06-15.
        $this->assertSame('2026-06-15', $contract->dueDate()->toDateString());

        // Month-end overflow, like strtotime('+1 months') on Jan 31 => Mar 3.
        $contract = Contract::factory()->create([
            'term_months' => 1,
            'started_at' => '2026-01-31 10:00:00',
        ]);
        $this->assertSame('2026-03-03', $contract->dueDate()->toDateString());
    }

    public function test_buy_back_price_is_not_floored(): void
    {
        $contract = Contract::factory()->make([
            'amount' => 150_000,
            'monthly_rate' => 10,
            'term_months' => 4,
        ]);

        // 150.000 + 150.000*0.10*4 = 210.000
        $this->assertSame(210_000.0, $contract->buyBackPrice());
    }

    public function test_expired_scope_uses_the_view_clock_without_plus_one(): void
    {
        Carbon::setTestNow('2026-07-18 10:00:00');

        // 5 whole calendar months since start > term 4 => expired.
        $expired = Contract::factory()->create([
            'term_months' => 4,
            'started_at' => '2026-02-01 00:00:00',
        ]);

        // Extensions push it out: 5 > 4 + 1.0 is false => not expired.
        $extended = Contract::factory()->create([
            'term_months' => 4,
            'started_at' => '2026-02-01 00:00:00',
        ]);
        ContractExtension::factory()->for($extended)->create(['months' => 1.0]);

        // 4 months since start is NOT > 4 => not expired (no +1 on this clock).
        $current = Contract::factory()->create([
            'term_months' => 4,
            'started_at' => '2026-03-10 00:00:00',
        ]);

        $ids = Contract::query()->expired()->pluck('id');

        $this->assertTrue($ids->contains($expired->id));
        $this->assertFalse($ids->contains($extended->id));
        $this->assertFalse($ids->contains($current->id));
    }
}
