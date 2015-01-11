<?php  namespace SICV\Contracts\Actions;

use SICV\Core\Commander\Command;

class CreateNewContractCommand extends Command {

    public $user_id;
    public $client_id;
    public $months;
    public $percentage;
    public $amount;

    public $articles_id = [];

    private $fields = [
        'user_id',
        'client_id',
        'months',
        'percentage',
        'amount'
    ];

    public function setArticlesIds($articles_id){
        $this->articles_id = $articles_id;
    }

    public function mapInputData($input, $client_id, $user_id, array $articles_id){
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (in_array($key, $this->fields) && !empty($value)) {
                    $this->$key = $value;
                }
            }
        }
        $this->amount = $this->normalizeAmount($this->amount);
        $this->client_id = $client_id;
        $this->user_id = $user_id;
        $this->articles_id = $articles_id;
    }

}