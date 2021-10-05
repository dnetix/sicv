<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Commander\Command;

class CreateNewContractCommand extends Command
{
    public $user_id;
    public $client_id;
    public $months;
    public $percentage;
    public $amount;

    public $articlesWithAmount = [];

    private $fields = [
        'user_id',
        'client_id',
        'months',
        'percentage',
        'amount',
    ];

    public function __construct($user_id, $client_id, $months, $percentage, $amount)
    {
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->months = $months;
        $this->percentage = $percentage;
        $this->amount = $this->normalizeAmount($amount);
    }

    public function setArticles($articlesWithAmount)
    {
        foreach ($articlesWithAmount as $index => $articleWithAmount) {
            $articlesWithAmount[$index]['amount'] = $this->normalizeAmount($articlesWithAmount[$index]['amount']);
        }
        $this->articlesWithAmount = $articlesWithAmount;
        return $this;
    }

    public function articlesWithAmount()
    {
        return $this->articlesWithAmount;
    }

    public function toAttributes()
    {
        $array = [];
        foreach ($this->fields as $field) {
            $array[$field] = $this->$field;
        }
        return $array;
    }
}
