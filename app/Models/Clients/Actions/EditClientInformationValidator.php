<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Validations\FormValidator;

class EditClientInformationValidator extends FormValidator
{
    protected $rules = [
        'name' => 'required',
    ];
}
