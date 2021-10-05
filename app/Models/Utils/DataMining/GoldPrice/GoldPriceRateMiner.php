<?php

namespace App\Models\Utils\DataMining\GoldPrice;

class GoldPriceRateMiner implements GoldPriceMiner
{
    private $url = 'http://www.goldpricerate.com/spanish/gold-price-in-colombia.php';
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
            $eraseAllButTableRegex = '/(?:<div\sid=\"gold_today">\s+)(<table[\s\S]+?<\/table>)/';
            preg_match($eraseAllButTableRegex, $this->data, $matches);
            $cleanHTMLPropertiesRegex = '/(<img[\s\S]+?>)|(&nbsp;)|( ?(?:class|style|colspan|cellpadding|cellspacing|width|rowspan) ?\=\"[\s\S]+?\" ?)/';
            $this->data = preg_replace($cleanHTMLPropertiesRegex, '', $matches[1]);
        } catch (\Exception $e) {
            throw new GoldPriceMinerException("Can't clean data");
        }
    }
}
