<?php

namespace App\Queries;

use App\Models\StoreItem;
use Illuminate\Database\Eloquent\Collection;

class SearchStoreItems
{
    /**
     * Legacy POS search semantics over available items: every token must
     * match; numeric tokens also match the item id or source contract id
     * (both by prefix), text tokens match the description.
     *
     * @return Collection<int, StoreItem>
     */
    public function __invoke(string $query, int $limit = 30): Collection
    {
        $tokens = preg_split('/\s+/', trim($query), flags: PREG_SPLIT_NO_EMPTY);

        if ($tokens === []) {
            return new Collection;
        }

        return StoreItem::query()
            ->available()
            ->where(function ($builder) use ($tokens) {
                foreach ($tokens as $token) {
                    $builder->where(function ($builder) use ($token) {
                        $builder->whereLike('description', "%$token%");

                        if (ctype_digit($token)) {
                            $builder->orWhereLike('id', "$token%")
                                ->orWhereLike('contract_id', "$token%");
                        }
                    });
                }
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }
}
