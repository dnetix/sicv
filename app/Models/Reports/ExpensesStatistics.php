<?php

namespace App\Models\Reports;

use App\Presenters\ExpensesStatisticsPresenter;
use App\Models\Utils\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Collection;

class ExpensesStatistics
{
    use PresentableTrait;
    protected $presenter = ExpensesStatisticsPresenter::class;

    private $expenses;

    public $totalByType;

    public function __construct(Collection $expenses)
    {
        $this->expenses = $expenses;
        $this->calculate();
    }

    public function calculate()
    {
        //TODO Find a way to do this on the database to improve performance
        $expensesByType = $this->expenses->groupBy('expense_type_id');
        $totalByType = [];
        foreach ($expensesByType as $expenseByType) {
            $total = 0;
            $type = $expenseByType[0]->expenseType->name();
            foreach ($expenseByType as $expense) {
                $total += $expense->amount();
            }
            $totalByType[$type] = $total;
        }
        $this->totalByType = $totalByType;
    }

    public function totalByType()
    {
        return $this->totalByType;
    }
}
