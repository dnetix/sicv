<?php namespace SICV\Core\Validations;


interface FactoryInterface {
    /**
     * Initialize validator
     *
     * @param array $formData
     * @param array $rules
     * @param array $messages
     * @return ValidatorInterface
     */
    public function make(array $formData, array $rules, array $messages = []);
}