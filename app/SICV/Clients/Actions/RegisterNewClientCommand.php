<?php namespace SICV\Clients\Actions;

use SICV\Commander\Command;

class RegisterNewClientCommand extends Command {

    public $name;
    public $id_number;
    public $id_type;
    public $id_expedition;
    public $address;
    public $cellnumber;
    public $phonenumber;
    public $email;
    public $city;

    public function __construct($input){
        if(is_array($input)){
            foreach($input as $key => $value){
                $this->$key = $value;
            }
        }
    }

}