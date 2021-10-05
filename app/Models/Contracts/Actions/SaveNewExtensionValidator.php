<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Validations\FormValidator;

class SaveNewExtensionValidator extends FormValidator
{
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'contract_id' => 'required|exists:contracts,id',
        'amount' => 'required|numeric|min:100',
    ];
}
