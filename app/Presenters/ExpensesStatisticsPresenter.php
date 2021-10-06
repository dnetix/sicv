<?php

namespace App\Presenters;

use App\Models\Utils\Presenters\Presenter;

class ExpensesStatisticsPresenter extends Presenter
{
    public function totalByTypeAsTable()
    {
        $totalByTypes = $this->entity->totalByType();
        $toReturn = [];
        foreach ($totalByTypes as $key => $value) {
            $toReturn[] = "<tr><td>{$key}</td><td>" . $this->toMoney($value) . '</td></tr>';
        }
        return implode("\n", $toReturn);
    }
}
