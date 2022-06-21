<?php

namespace App\Repositories;

use App\Models\Articles\Article;
use App\Models\Clients\Client;
use App\Models\Contracts\Annul;
use App\Models\Contracts\Contract;
use App\Models\Contracts\ContractStates;
use App\Models\Contracts\Extension;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContractRepository
{
    public function save(Contract $contract): Contract
    {
        $contract->save();
        return $contract;
    }

    /**
     * @param int $limit
     * @return Contract[]
     */
    public function getLastContracts(int $limit = 30): Collection
    {
        return Contract::orderBy('id', 'desc')
            ->with(['client', 'articles'])
            ->limit($limit)->get();
    }

    public function associateWithArticle(Contract $contract, Article $article, float $amount)
    {
        $contract->articles()->attach($article, ['article_amount' => $amount]);
    }

    public function getContractsOfDay($day = null)
    {
        if (is_null($day)) {
            $day = date('Y-m-d');
        }
        //TODO use the DateHelper to SQL here
        return Contract::whereBetween('created_at', [$day . ' 00:00:00', $day . ' 23:59:59'])->with(['client', 'articles'])->orderBy('id', 'desc')->limit(1000)->get();
    }

    public function getContractsOfClient(Client $client)
    {
        return $client->contracts()->orderBy('id', 'desc')->get();
    }

    /**
     * @param $id
     * @return \App\Models\Contracts\Contract
     */
    public function getContractById($id)
    {
        return Contract::with('articles', 'articles.articleType', 'extensions')->findOrFail($id);
    }

    public function saveExtension(Extension &$extension)
    {
        return $extension->save();
    }

    public function update($contract)
    {
        return $contract->save();
    }

    public function getExpiredContracts($monthDifference = 0)
    {
        //TODO Look for a solution to change names in the database and not need to change in here
        $contractIDS = DB::select(DB::raw('SELECT x.id FROM (SELECT contracts.id, TIMESTAMPDIFF(MONTH, contracts.created_at, NOW()) AS actual_months, (contracts.months + FLOOR(IFNULL(SUM(extensions.amount), 0) / (contracts.amount * (contracts.percentage / 100)))) AS contract_months FROM contracts
            LEFT JOIN extensions ON contracts.id = extensions.contract_id
            WHERE contracts.state = :state
            GROUP BY contracts.id) AS x
            WHERE x.actual_months > x.contract_months
            LIMIT :limit'), ['state' => ContractStates::ACTIVE, 'limit' => config()->get('sicv.max_expired_contracts')]);
        //TODO Create a Util class to handle this kind of stuff, makes the array of arrays an array with the form I need
        $contractIDS = array_column($contractIDS, 'id');
        if (count($contractIDS) >= 1) {
            return Contract::whereIn('id', $contractIDS)->with(['extensions', 'articles', 'client', 'extensions', 'preSellout'])->get();
        } else {
            return new Collection();
        }
    }

    public function getPreselloutContracts()
    {
        $contractIDS = DB::select(DB::raw('SELECT contract_id FROM pre_sellouts'));
        // Creates just an array removing the array of arrays
        $contractIDS = array_column($contractIDS, 'contract_id');
        if (count($contractIDS) >= 1) {
            return Contract::whereIn('id', $contractIDS)->with(['extensions', 'articles', 'client', 'extensions', 'preSellout'])->get();
        } else {
            return new Collection();
        }
    }

    public function saveAnnul(Annul $annul): Annul
    {
        $annul->save();
        return $annul;
    }

    /**
     * @param Contract $contract
     * @return Article[]
     */
    public function getContractArticles(Contract $contract)
    {
        return $contract->articles;
    }
}
