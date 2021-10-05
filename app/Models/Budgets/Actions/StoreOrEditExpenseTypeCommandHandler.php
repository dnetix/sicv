<?php

namespace App\Models\Budgets\Actions;

use App\Models\Budgets\BudgetRepository;
use App\Models\Budgets\ExpenseType;
use App\Models\Core\Commander\CommandHandler;

class StoreOrEditExpenseTypeCommandHandler implements CommandHandler
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
        if (is_null($command->id)) {
            $expenseType = new ExpenseType();
        } else {
            $expenseType = $this->budgetRepository->getExpenseTypeById($command->id);
        }
        $expenseType->setName($command->name);
        $this->budgetRepository->saveExpenseType($expenseType);
        return $expenseType;
    }
}
