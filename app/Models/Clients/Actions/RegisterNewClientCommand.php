<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Commander\Command;

class RegisterNewClientCommand extends Command
{
    public $name;
    public $id_number;
    public $id_type;
    public $id_expedition;
    public $address;
    public $cell_number;
    public $phone_number;
    public $email;
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
        'city',
    ];

    public function __construct($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (in_array($key, $this->fields) && !empty($value)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
