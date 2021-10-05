<?php

namespace App\Models\Articles\Actions;

use App\Models\Core\Validations\FormValidator;

class CreateOrRetrieveArticleValidator extends FormValidator
{
    protected $rules = [
        'description' => 'required',
        'article_type_id' => 'required|numeric',
    ];
}
