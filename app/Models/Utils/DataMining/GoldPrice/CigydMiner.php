<?php

namespace App\Models\Utils\DataMining\GoldPrice;

class CigydMiner implements GoldPriceMiner
{
    private $url = 'http://www.cigydltda.com/precio-oro-hoy';
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
        $eraseAllButTableRegex = '/(<table id=\"datos\">[\s\S]+?<\/table>)/';
        preg_match($eraseAllButTableRegex, $this->data, $matches);
        $cleanHTMLPropertiesRegex = '/(<img[\s\S]+?>)|(&nbsp;)|( ?(?:class|style|colspan|cellpadding|cellspacing|width|rowspan) ?\=\"[\s\S]+?\" ?)/';
        $this->data = preg_replace($cleanHTMLPropertiesRegex, '', $matches[1]);
    }
}
