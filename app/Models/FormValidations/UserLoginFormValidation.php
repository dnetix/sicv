<?php

namespace App\Models\FormValidations;

use App\Models\Core\Validations\FormValidator;

class UserLoginFormValidation extends FormValidator
{
    protected $rules = [
        'username' => 'required|alpha_num',
        'password' => 'required',
    ];
}
