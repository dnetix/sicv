<?php namespace SICV\Articles\Actions;

use SICV\Core\Validations\FormValidator;

class CreateOrRetrieveArticleValidator extends FormValidator {

    protected $rules = [
        'description' => 'required',
        'article_type_id' => 'required|numeric',
    ];

}