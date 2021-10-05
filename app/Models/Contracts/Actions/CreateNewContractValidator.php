<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Validations\FormValidator;

class CreateNewContractValidator extends FormValidator
{
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'client_id' => 'required|exists:clients,id',
        'months' => 'required|numeric',
        'percentage' => 'required|numeric',
        'amount' => 'required|numeric|min:5000',
    ];
}
