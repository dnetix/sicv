<?php

namespace App\Models\Contracts\Actions;

use App\Helpers\Dates\DateHelper;
use App\Helpers\RepositoryHelper;
use App\Models\Articles\Article;
use App\Models\Clients\ClientNote;
use App\Models\Contracts\Contract;
use App\Models\Contracts\ContractStates;

class CreateNewContractAction
{
    private int $user_id;
    private int $client_id;
    private int $months;
    private float $percentage;
    private array $articles;
    private array $note;

    public function __construct(int $user_id, int $client_id, int $months, float $percentage, array $articles, array $note = [])
    {
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->months = $months;
        $this->percentage = $percentage;
        $this->articles = $articles;
        $this->note = $note;
    }

    public function execute(): Contract
    {
        $amount = 0;
        foreach ($this->articles as $article) {
            $amount += $article['amount'];
        }

        $contract = new Contract([
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'state' => ContractStates::ACTIVE,
            'months' => $this->months,
            'amount' => $amount,
            'percentage' => $this->percentage,
            'created_at' => DateHelper::now()->toSQLTimestamp(),
            'end_date' => DateHelper::create('+' . $this->months . ' months')->toSQLDate(),
        ]);
        $contract = RepositoryHelper::forContracts()->save($contract);

        foreach ($this->articles as $data) {
            $article = new Article($data);
            $article = RepositoryHelper::forArticles()->storeArticle($article);
            RepositoryHelper::forContracts()->associateWithArticle($contract, $article, $data['amount']);
        }

        if ($this->note) {
            // TODO: Refactor to an action
            $note = new ClientNote([
                'note' => $this->note['note'],
                'user_id' => $this->user_id,
                'client_id' => $this->client_id,
                'contract_id' => $contract->id(),
                'importance' => $this->note['importance'],
            ]);
            RepositoryHelper::forClients()->saveClientNote($note);
        }

        return $contract;
    }
}
