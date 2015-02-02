<?php

use SICV\Budgets\Actions\SaveNewExpenseCommand;
use SICV\Budgets\Actions\StoreOrEditExpenseTypeCommand;
use SICV\Budgets\BudgetRepository;
use SICV\Reports\ExpensesStatistics;

class BudgetController extends BaseController {


    /**
     * @var BudgetRepository
     */
    private $budgetRepository;

    function __construct(BudgetRepository $budgetRepository, \SICV\Core\Commander\CommandBus $commandBus) {
        //TODO Refactor to not require repositories in any controller just Commands
        $this->budgetRepository = $budgetRepository;
        parent::__construct($commandBus);
    }

    public function expenses(){

        if(Input::has('startDate')){
            $startDate = Input::get('startDate');
            $endDate = Input::get('endDate');
        }else{
            $startDate = Date::create()->changeMonths(-1)->toSQLDate();
            $endDate = Date::create()->toSQLDate();
        }

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        // TODO Command
        $expenses = $this->budgetRepository->getExpenses([
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        $data['expenses'] = $expenses;
        $data['totalExpenses'] = $expenses->sum('amount');

        //TODO Should be a better way
        $data['expensesStatistics'] = new ExpensesStatistics($expenses);

        $expenseTypes = $this->budgetRepository->getExpenseTypes();
        //TODO Create a Util class to handle this kind of stuff, makes the array of arrays an array with the form I need
        $data['expenseTypes'] = array_column($expenseTypes->toArray(), 'name', 'id');

        //TODO refactor the totalExpenses to somehow not use the money format
        return View::make('budget.expenses', $data);
    }
    
    public function storeExpense(){
        $command = new SaveNewExpenseCommand(
            Input::get('amount'),
            Input::get('description'),
            Input::get('expense_type_id'),
            Auth::id()
        );
        $this->execute($command);

        return Redirect::route('budget.expenses');
    }

    public function expenseTypes(){
        //TODO Create a Util class to handle this kind of stuff, makes the array of arrays an array with the form I need
        $data['expenseTypes'] = $this->budgetRepository->getExpenseTypes();

        return View::make('budget.expense_types', $data);
    }

    public function expenseType(){
        $id = Input::get('id');
        $expense = $this->budgetRepository->getExpenseTypeById($id);
        return $expense;
    }

    public function storeExpenseType(){
        $command = new StoreOrEditExpenseTypeCommand(
            Input::get('name'),
            Input::get('id')
        );
        $this->execute($command);
        return Redirect::route('budget.expensetypes');
    }

}
