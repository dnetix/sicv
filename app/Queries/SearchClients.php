<?php

namespace App\Queries;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class SearchClients
{
    /**
     * Legacy search semantics: the query is split on whitespace and every
     * token must match either the start of the document number or any part
     * of the name.
     *
     * @return Collection<int, Client>
     */
    public function __invoke(string $query, int $limit = 15): Collection
    {
        $tokens = preg_split('/\s+/', trim($query), flags: PREG_SPLIT_NO_EMPTY);

        if ($tokens === []) {
            return new Collection;
        }

        return Client::query()
            ->where(function ($builder) use ($tokens) {
                foreach ($tokens as $token) {
                    $builder->where(function ($builder) use ($token) {
                        $builder->whereLike('document_number', "$token%")
                            ->orWhereLike('name', "%$token%");
                    });
                }
            })
            ->with('notes.user')
            ->withCount('contracts')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }
}
