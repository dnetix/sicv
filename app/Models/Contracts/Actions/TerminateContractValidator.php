<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Validations\FormValidator;

class TerminateContractValidator extends FormValidator
{
    protected $rules = [
        'contract_id' => 'required|exists:contracts,id',
        'end_amount' => 'required|numeric|min:100',
    ];
}
