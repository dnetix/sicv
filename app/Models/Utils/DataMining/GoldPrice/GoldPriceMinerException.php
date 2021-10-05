<?php

namespace App\Models\Utils\DataMining\GoldPrice;

class GoldPriceMinerException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
