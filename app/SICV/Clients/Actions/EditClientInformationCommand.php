<?php namespace SICV\Clients\Actions;

use SICV\Commander\Command;

class EditClientInformationCommand extends Command {

    public $id;

    public $name;
    public $id_number;
    public $id_type;
    public $id_expedition;
    public $address;
    public $cellnumber;
    public $phonenumber;
    public $email;
    public $city;

    public function __construct($id, $input){
        $this->id = $id;
        if(is_array($input)){
            foreach($input as $key => $value){
                $this->$key = $value;
            }
        }
    }

}