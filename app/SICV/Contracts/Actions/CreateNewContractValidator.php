<?php  namespace SICV\Contracts\Actions;

use SICV\Validations\FormValidator;

class CreateNewContractValidator extends FormValidator {

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'client_id' => 'required|exists:clients,id',
        'months' => 'required|numeric',
        'percentage' => 'required|numeric',
        'amount' => 'required|numeric'
    ];

}