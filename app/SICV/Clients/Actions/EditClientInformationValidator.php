<?php namespace SICV\Clients\Actions;

use SICV\Core\Validations\FormValidator;

class EditClientInformationValidator extends FormValidator {

    protected $rules = [
        'name' => 'required'
    ];

}