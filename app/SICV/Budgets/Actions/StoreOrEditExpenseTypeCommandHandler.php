<?php  namespace SICV\Budgets\Actions;


use SICV\Budgets\BudgetRepository;
use SICV\Budgets\ExpenseType;
use SICV\Core\Commander\CommandHandler;

class StoreOrEditExpenseTypeCommandHandler implements CommandHandler {

    /**
     * @var BudgetRepository
     */
    private $budgetRepository;

    function __construct(BudgetRepository $budgetRepository) {
        $this->budgetRepository = $budgetRepository;
    }

    public function handle($command) {
        if(is_null($command->id)){
            $expenseType = new ExpenseType();
        }else{
            $expenseType = $this->budgetRepository->getExpenseTypeById($command->id);
        }
        $expenseType->setName($command->name);
        $this->budgetRepository->saveExpenseType($expenseType);
        return $expenseType;
    }

}