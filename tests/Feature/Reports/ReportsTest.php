<?php

namespace Tests\Feature\Reports;

use App\Enums\ContractStatus;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Sale;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_all_report_routes_require_authentication(): void
    {
        // The legacy app left the financial reports unauthenticated; every
        // report now requires login.
        foreach (['expired', 'queued', 'active', 'extensions', 'sold', 'financial', 'expenses', 'pulled', 'redeemed', 'stats'] as $report) {
            $this->get(route("reports.$report"))->assertRedirect(route('login'));
        }
    }

    public function test_expired_report_math_uses_view_clock_and_unfloored_monthly(): void
    {
        Carbon::setTestNow('2026-07-18 10:00:00');

        // Started 6 whole months ago, term 4, 1.5 months extended:
        // view clock elapsed = 6 (no +1), behind = 4.5 months.
        $contract = Contract::factory()->create([
            'amount' => 999_999,
            'monthly_rate' => 10,
            'term_months' => 4,
            'started_at' => '2026-01-18 00:00:00',
        ]);
        ContractExtension::factory()->for($contract)->create(['months' => 1.5]);

        $response = $this->actingAs($this->user)->get(route('reports.expired'));
        $response->assertOk()->assertSee($contract->client->name);

        $row = Contract::query()->withReportColumns()->expired()->sole();

        $this->assertSame(6, (int) $row->view_months_elapsed);
        // Unfloored monthly charge: 999.999 * 0.10 = 99.999,9
        $this->assertSame(99_999.9, round($row->reportMonthlyCharge(), 1));
        // Owed = (6 - 1.5) * 99.999,9 = 449.999,55; payoff adds the capital.
        $this->assertSame(449_999.55, round($row->reportOwed(), 2));
        $this->assertSame(1_449_998.55, round($row->reportPayoff(), 2));
    }

    public function test_expired_report_excludes_queued_contracts(): void
    {
        $visible = Contract::factory()->startedMonthsAgo(6)->create();
        $queued = Contract::factory()->startedMonthsAgo(6)->create();
        $queued->repossession()->create(['queued_at' => now(), 'user_id' => $this->user->id]);

        $this->actingAs($this->user)->get(route('reports.expired'))
            ->assertSee(route('contracts.show', $visible))
            ->assertDontSee(route('contracts.show', $queued));

        $this->actingAs($this->user)->get(route('reports.queued'))
            ->assertSee(route('contracts.show', $queued))
            ->assertDontSee(route('contracts.show', $visible));
    }

    public function test_financial_report_shows_daily_flows(): void
    {
        Carbon::setTestNow('2026-07-18 10:00:00');

        // Money out: a new loan and an expense today; a direct store purchase.
        Contract::factory()->create(['amount' => 400_000]);
        Expense::query()->create([
            'expense_type_id' => ExpenseType::factory()->create()->id,
            'amount' => 50_000,
            'spent_at' => now(),
            'user_id' => $this->user->id,
        ]);
        StoreItem::factory()->create(['cost' => 30_000]);

        // Money in: an extension payment and a redemption.
        $extended = Contract::factory()->create(['amount' => 1_000_000, 'started_at' => now()->subMonths(8)]);
        ContractExtension::factory()->for($extended)->create(['amount' => 100_000, 'paid_at' => now()]);

        Contract::factory()->create([
            'amount' => 200_000,
            'status' => ContractStatus::Redeemed,
            'started_at' => now()->subMonths(2),
            'ended_at' => now(),
            'settled_amount' => 240_000,
        ]);

        $this->actingAs($this->user)->get(route('reports.financial'))
            ->assertOk()
            // Out: 400k + 1M (extended contract created today too) + 50k + 30k
            ->assertSee('Total salidas')
            ->assertSee('Total entradas')
            ->assertSee(money(100_000))   // extensions
            ->assertSee(money(240_000));  // redemption collected
    }

    public function test_stats_report_groups_contracts_by_month(): void
    {
        Contract::factory()->create(['amount' => 100_000, 'started_at' => '2025-03-10 10:00:00']);
        Contract::factory()->create(['amount' => 200_000, 'started_at' => '2025-03-20 10:00:00']);
        Contract::factory()->create(['amount' => 400_000, 'started_at' => '2025-12-31 23:00:00']);

        $this->actingAs($this->user)->get(route('reports.stats', ['year' => 2025]))
            ->assertOk()
            ->assertSee(money(300_000))
            // Dec 31 datetimes are included (a legacy report bug, fixed).
            ->assertSee(money(400_000))
            ->assertSee(money(700_000));
    }

    public function test_sold_report_lists_sale_lines_in_range(): void
    {
        $item = StoreItem::factory()->create();
        $sale = Sale::query()->create([
            'client_id' => Client::factory()->create()->id,
            'sold_at' => today(),
            'total' => 80_000,
            'warranty_days' => 0,
            'user_id' => $this->user->id,
        ]);
        $sale->items()->create(['store_item_id' => $item->id, 'price' => 80_000, 'quantity' => 1]);

        $this->actingAs($this->user)->get(route('reports.sold'))
            ->assertOk()
            ->assertSee("NC{$sale->id}")
            ->assertSee(money(80_000));
    }
}
