<?php  namespace SICV\Contracts;

use SICV\Clients\Client;
use SICV\Utils\Dates\DateHelper;

class ContractRepository {

    public function create(&$contract) {
        $contract->save();
    }

    /**
     * Associates a contract with the articles sended as parameter
     * @param $contract
     * @param array $articles_id
     */
    public function associateWithArticles(Contract &$contract, array $articles_id) {
        foreach($articles_id as $article_id){
            $contract->articles()->attach($article_id);
        }
    }

    public function getContractsOfDay($day = null){
        if(is_null($day)){
            $day = date('Y-m-d');
        }
        return Contract::whereBetween('created_at', [$day.' 00:00:00', $day.' 23:59:59'])->with(['client', 'articles'])->orderBy('id', 'desc')->get();
    }



    public function getContractsOfClient(Client $client) {
        return $client->contracts()->orderBy('id', 'desc')->get();
    }

    /**
     * @param $id
     * @return \SICV\Contracts\Contract
     */
    public function getContractById($id) {
        return Contract::with('articles', 'articles.articleType', 'extensions')->findOrFail($id);
    }

    public function saveExtension(Extension &$extension) {
        return $extension->save();
    }

    public function update($contract) {
        return $contract->save();
    }

    public function getExpiredContracts($monthDifference = 0) {

//        $contracts = Contract::whereIn('state', [ContractStates::ACTIVE])->with(['extensions'])->get();
//        foreach($contracts as $key => $contract){
//            if($contract->calculatedMonths() > $monthDifference){
//                $contracts->forget($key);
//            }
//            echo $contract->id();
//        }
//        return $contracts;
        return Contract::whereIn('state', [ContractStates::ACTIVE])->where('id', '<', 1000)->with(['extensions', 'articles', 'client'])->get();

    }

    public function saveAnnul(Annul &$annul) {
        return $annul->save();
    }

}