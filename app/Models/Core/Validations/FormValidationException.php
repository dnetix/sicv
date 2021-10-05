<?php

namespace App\Models\Core\Validations;

class FormValidationException extends \Exception
{
    /**
     * @var mixed
     */
    protected $errors;
    /**
     * @param string $message
     * @param mixed  $errors
     */
    public function __construct($message, $errors)
    {
        $this->errors = $errors;
        parent::__construct($message);
    }
    /**
     * Get form validation errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
