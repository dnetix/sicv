<?php

namespace App\Models\Clients\Actions;

use App\Models\Core\Commander\Command;

class EditClientInformationCommand extends Command
{
    public $id;

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

    public $fieldsToEdit = [];

    public function __construct($id, $input)
    {
        $this->id = $id;
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (in_array($key, $this->fields)) {
                    $this->fieldsToEdit[] = $key;
                    $this->$key = (empty($value) ? null : $value);
                }
            }
        }
    }
}
