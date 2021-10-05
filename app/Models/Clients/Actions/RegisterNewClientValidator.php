<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Validations\FormValidator;

class RegisterNewClientValidator extends FormValidator
{
    protected $rules = [
        'name' => 'required',
        'id_number' => 'required|numeric',
        'id_type' => 'required',
        'id_expedition' => 'required',
    ];
}
