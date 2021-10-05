<?php

namespace App\Models\Contracts\Actions;

use App\Models\Core\Commander\Command;

class AnnulContractCommand extends Command
{
    public $created_at;
    public $note;
    public $contract_id;
    public $user_id;
    public $password;

    private $fields = [
        'created_at',
        'note',
        'contract_id',
        'user_id',
        'password',
    ];

    public function __construct($data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields) && !empty($value)) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @param mixed $contract_id
     */
    public function setContractId($contract_id)
    {
        $this->contract_id = $contract_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
