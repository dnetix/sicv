<?php namespace SICV\Validations;


interface ValidatorInterface {
    /**
     * Determine if the validation failed
     *
     * @return mixed
     */
    public function fails();
    /**
     * Get the list of validation errors
     *
     * @return mixed
     */
    public function errors();
}