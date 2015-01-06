<?php namespace SICV\Clients\Actions;

use SICV\Validations\FormValidator;

class EditClientInformationValidator extends FormValidator {

    protected $rules = [
        'name' => 'required',
        'id_number' => 'required|numeric',
        'id_type' => 'required',
        'id_expedition' => 'required'
    ];

}