<?php namespace SICV\FormValidations;

use SICV\Core\Validations\FormValidator;

class UserLoginFormValidation extends FormValidator {

    protected $rules = [
        'username' => 'required|alpha_num',
        'password' => 'required'
    ];

}