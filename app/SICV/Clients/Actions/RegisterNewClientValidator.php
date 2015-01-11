<?php namespace SICV\Clients\Actions;

use SICV\Core\Validations\FormValidator;

class RegisterNewClientValidator extends FormValidator {

    protected $rules = [
        'name' => 'required',
        'id_number' => 'required|numeric',
        'id_type' => 'required',
        'id_expedition' => 'required'
    ];

}