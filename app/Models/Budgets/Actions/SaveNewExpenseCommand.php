<?php

namespace App\Models\Budgets\Actions;

use App\Models\Core\Commander\Command;

class SaveNewExpenseCommand extends Command
{
    public $amount;
    public $description;
    public $expense_type_id;
    public $user_id;

    public function __construct($amount, $description, $expense_type_id, $user_id)
    {
        $this->amount = $this->normalizeAmount($amount);
        $this->description = $description;
        $this->expense_type_id = $expense_type_id;
        $this->user_id = $user_id;
    }
}
