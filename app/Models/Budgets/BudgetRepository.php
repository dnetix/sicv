<?php

namespace App\Models\Budgets;

use App\Models\Utils\Dates\DateHelper;

class BudgetRepository
{
    public function saveExpense(Expense &$expense)
    {
        $expense->save();
    }

    public function getExpenses($filters = [])
    {
        $expenses = Expense::orderBy('created_at', 'desc');
        if (isset($filters['startDate']) && isset($filters['endDate'])) {
            $expenses->whereBetween('created_at', [DateHelper::create($filters['startDate'])->toSQLReport(), DateHelper::create($filters['endDate'])->toSQLReport(true)]);
        }
        $expenses->with(['user', 'expenseType']);
        return $expenses->get();
    }

    public function saveExpenseType(ExpenseType &$expenseType)
    {
        $expenseType->save();
    }

    public function getExpenseTypes()
    {
        return ExpenseType::orderBy('name')->get();
    }

    public function getExpenseTypeById($id)
    {
        return ExpenseType::findOrFail($id);
    }
}
