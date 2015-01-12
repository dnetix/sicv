<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Validations\FormValidator;

class TerminateContractValidator extends FormValidator {

    protected $rules = [
        'contract_id' => 'required|exists:contracts,id',
        'end_amount' => 'required|numeric|min:100'
    ];

}