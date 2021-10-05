<?php

namespace App\Models\Utils\DataMining\GoldPrice;

use Exception;

class GoldPriceTickerMiner implements GoldPriceMiner
{
    private $url = 'http://www.goldpriceticker.com/es/gold-rates/colombia/';
    private $data;

    public function getGoldInformation()
    {
        $this->getUrlContents();
        $this->cleanData();
        return $this->data;
    }

    /**
     * @return string
     */
    public function getUrlContents()
    {
        $this->data = file_get_contents($this->url);
    }

    private function cleanData()
    {
        try {
            $eraseAllButTableRegex = '/\<div class\=\"historical\"\>[\s\S]+?\<\/div\>/';
            preg_match($eraseAllButTableRegex, $this->data, $matches);
            $cleanHTMLPropertiesRegex = '/(<img[\s\S]+?>)|(&nbsp;)|( ?(?:class|style|colspan|cellpadding|cellspacing|width|rowspan) ?\=\"[\s\S]+?\" ?)/';
            $this->data = preg_replace($cleanHTMLPropertiesRegex, '', $matches[0]);
        } catch (Exception $e) {
            throw new GoldPriceMinerException("Can't clean data: " . $e->getMessage());
        }
    }
}
