<?php namespace SICV\Validations;

use Illuminate\Validation\Factory as Validator;

class LaravelValidator {

    /**
     * @var Validator
     */
    private $validator;

    function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function make(array $formData, array $rules, array $messages = []){
        return $this->validator->make($formData, $rules, $messages);
    }
}