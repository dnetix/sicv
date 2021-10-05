<?php

namespace App\Models\Budgets\Actions;

use App\Models\Core\Validations\FormValidator;

class SaveNewExpenseValidator extends FormValidator
{
    protected $rules = [
        'amount' => 'required|numeric|min:100',
        'description' => 'required',
        'expense_type_id' => 'required|exists:expense_types,id',
        'user_id' => 'required',
    ];
}
