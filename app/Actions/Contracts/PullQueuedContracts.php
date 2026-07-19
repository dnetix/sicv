<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use App\Models\ItemType;
use App\Models\User;

class PullQueuedContracts
{
    public function __construct(
        private readonly ForfeitContract $forfeit,
        private readonly ScrapContract $scrap,
    ) {}

    /**
     * The bulk "saca": each selected queued contract is either forfeited to
     * the store at the given price, or — for gold, when scrapping was chosen
     * — scrapped without entering inventory.
     *
     * @param  array<int, int>  $prices  contract id => asking price
     * @return array{forfeited: int, scrapped: int}
     */
    public function __invoke(array $prices, bool $scrapGold, User $user): array
    {
        $result = ['forfeited' => 0, 'scrapped' => 0];

        $contracts = Contract::query()
            ->active()
            ->whereIn('id', array_keys($prices))
            ->get();

        foreach ($contracts as $contract) {
            if ($scrapGold && $contract->item_type_id === ItemType::GOLD) {
                ($this->scrap)($contract);
                $result['scrapped']++;
            } else {
                // The bulk screen suggests the loan amount as price.
                ($this->forfeit)($contract, $prices[$contract->id], $user, $contract->amount);
                $result['forfeited']++;
            }
        }

        return $result;
    }
}
