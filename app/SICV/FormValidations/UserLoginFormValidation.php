<?php namespace SICV\FormValidations;

use SICV\Validations\FormValidator;

class UserLoginFormValidation extends FormValidator {

    protected $rules = [
        'username' => 'required|alpha_num',
        'password' => 'required'
    ];

}