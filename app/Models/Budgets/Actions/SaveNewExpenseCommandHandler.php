<?php

namespace App\Models\Budgets\Actions;

use App\Models\Budgets\BudgetRepository;
use App\Models\Budgets\Expense;
use App\Models\Core\Commander\CommandHandler;

class SaveNewExpenseCommandHandler implements CommandHandler
{
    /**
     * @var BudgetRepository
     */
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;
    }

    public function handle($command)
    {
        $expense = Expense::create([
            'amount' => $command->amount,
            'description' => $command->description,
            'expense_type_id' => $command->expense_type_id,
            'user_id' => $command->user_id,
        ]);

        $this->budgetRepository->saveExpense($expense);

        return $expense;
    }
}
