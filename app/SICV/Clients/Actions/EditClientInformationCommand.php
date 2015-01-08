<?php namespace SICV\Clients\Actions;

use SICV\Commander\Command;

class EditClientInformationCommand extends Command {

    public $id;

    public $name;
    public $id_number;
    public $id_type;
    public $id_expedition;
    public $address;
    public $cell_number;
    public $phone_number;
    public $email = null;
    public $city;

    private $fields = [
        'name',
        'id_number',
        'id_type',
        'id_expedition',
        'address',
        'cell_number',
        'phone_number',
        'email',
        'city'
    ];

    public function __construct($id, $input){
        $this->id = $id;
        if(is_array($input)){
            foreach($input as $key => $value){
                if(in_array($key, $this->fields) && !empty($value)) {
                    $this->$key = $value;
                }
            }
        }
    }

}