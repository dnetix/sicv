<?php

namespace App\Models\Budgets\Actions;

use App\Models\Core\Commander\Command;

class StoreOrEditExpenseTypeCommand extends Command
{
    public $id;
    public $name;

    public function __construct($name, $id = null)
    {
        $this->name = $name;
        $this->id = (empty($id) ? null : $id);
    }
}
