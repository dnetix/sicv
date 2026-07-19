<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Date;

#[Fillable([
    'client_id', 'description', 'item_type_id', 'weight_grams', 'amount',
    'monthly_rate', 'term_months', 'status', 'started_at', 'ended_at',
    'settled_amount', 'void_id', 'user_id',
])]
class Contract extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContractStatus::class,
            'weight_grams' => 'float',
            'monthly_rate' => 'float',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<ItemType, $this>
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ContractExtension, $this>
     */
    public function extensions(): HasMany
    {
        return $this->hasMany(ContractExtension::class);
    }

    /**
     * @return BelongsTo<ContractVoid, $this>
     */
    public function void(): BelongsTo
    {
        return $this->belongsTo(ContractVoid::class, 'void_id');
    }

    /**
     * @return HasOne<StoreItem, $this>
     */
    public function storeItem(): HasOne
    {
        return $this->hasOne(StoreItem::class);
    }

    public function repossession(): HasOne
    {
        return $this->hasOne(RepossessionEntry::class);
    }

    public function isActive(): bool
    {
        return $this->status === ContractStatus::Active;
    }

    public function isQueued(): bool
    {
        return $this->repossession()->exists();
    }

    /**
     * Expiry check for a single contract, matching scopeExpired()'s view
     * clock: full months from started_at to today (NO +1) exceed the term
     * plus the extended months.
     */
    public function isExpired(): bool
    {
        return $this->isActive()
            && floor($this->started_at->diffInMonths(Date::today()))
                > $this->term_months + $this->extendedMonths();
    }

    /*
    |--------------------------------------------------------------------------
    | Business math (exact legacy formulas — do not "fix" without a decision)
    |--------------------------------------------------------------------------
    */

    /**
     * Monthly interest charge in pesos: floor(amount × rate / 100).
     */
    public function monthlyInterest(): int
    {
        return (int) floor($this->amount * ($this->monthly_rate / 100));
    }

    /**
     * Months the contract has run, charged per started calendar month: the
     * calendar-month difference between the start date-time and today at
     * midnight (or the exit date-time once closed), plus one — a contract
     * redeemed the same day already owes one month.
     */
    public function monthsElapsed(): int
    {
        $end = $this->isActive() || $this->ended_at === null
            ? Date::today()
            : $this->ended_at;

        $diff = CarbonImmutable::make($this->started_at)->diff($end);

        return $diff->y * 12 + $diff->m + 1;
    }

    /**
     * Months bought by extension payments — fractional, unrounded.
     */
    public function extendedMonths(): float
    {
        return (float) $this->extensions->sum('months');
    }

    /**
     * Suggested payoff: outstanding months of interest plus the capital.
     * Operators may override the final figure (audited).
     */
    public function payoffAmount(): float
    {
        return ($this->monthsElapsed() - $this->extendedMonths()) * $this->monthlyInterest() + $this->amount;
    }

    /**
     * Due date: start plus the whole part of term + extended months
     * (fractional extension credit only moves the date once it adds up to
     * a full month).
     */
    public function dueDate(): CarbonImmutable
    {
        return CarbonImmutable::make($this->started_at)
            ->addMonths((int) floor($this->term_months + $this->extendedMonths()))
            ->startOfDay();
    }

    /**
     * Buy-back price printed on the legal contract: capital plus interest
     * over the original term (unfloored, prórrogas not involved).
     */
    public function buyBackPrice(): float
    {
        return $this->amount + ($this->amount * ($this->monthly_rate / 100) * $this->term_months);
    }

    /**
     * For a contract sold from the store: the sale line that moved it.
     */
    public function saleInfo(): ?SaleItem
    {
        return SaleItem::query()
            ->whereHas('storeItem', fn ($query) => $query->where('contract_id', $this->id))
            ->with('sale.client')
            ->latest('id')
            ->first();
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', ContractStatus::Active);
    }

    /**
     * Expired contracts, as defined by the legacy SQL views: active, and the
     * calendar months since the start (this clock has NO +1) exceed the term
     * plus the extended months.
     *
     * @param  Builder<static>  $query
     */
    public function scopeExpired(Builder $query): void
    {
        $query->active()->whereRaw(
            'TIMESTAMPDIFF(MONTH, started_at, CURDATE()) > term_months + COALESCE((
                SELECT SUM(months) FROM contract_extensions
                WHERE contract_extensions.contract_id = contracts.id
            ), 0)'
        );
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeNotQueued(Builder $query): void
    {
        $query->whereDoesntHave('repossession');
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeQueued(Builder $query): void
    {
        $query->whereHas('repossession');
    }

    /**
     * Adds the two columns the legacy report views exposed: months elapsed
     * on the VIEW clock (TIMESTAMPDIFF, no +1 — unlike monthsElapsed()) and
     * the total extended months.
     *
     * @param  Builder<static>  $query
     */
    public function scopeWithReportColumns(Builder $query): void
    {
        $query->select('contracts.*')->selectRaw(
            'TIMESTAMPDIFF(MONTH, started_at, CURDATE()) AS view_months_elapsed,
             COALESCE((
                SELECT SUM(months) FROM contract_extensions
                WHERE contract_extensions.contract_id = contracts.id
             ), 0) AS extended_months_total'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Report figures (legacy report views used the UNFLOORED monthly charge
    | and the view clock — intentionally different from the detail page)
    |--------------------------------------------------------------------------
    */

    public function reportMonthlyCharge(): float
    {
        return $this->amount * ($this->monthly_rate / 100);
    }

    /**
     * Months behind on payments, from the report columns.
     */
    public function reportMonthsBehind(): float
    {
        return $this->view_months_elapsed - (float) $this->extended_months_total;
    }

    public function reportOwed(): float
    {
        return $this->reportMonthsBehind() * $this->reportMonthlyCharge();
    }

    public function reportPayoff(): float
    {
        return $this->amount + $this->reportOwed();
    }
}
