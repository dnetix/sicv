<?php  namespace SICV\Contracts;

use SICV\Clients\Client;

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
        return $client->contracts;
    }

    public function getContractById($id) {
        return Contract::with('articles', 'articles.articleType', 'extensions')->findOrFail($id);
    }

    public function saveExtension(Extension &$extension) {
        return $extension->save();
    }

    public function update($contract) {
        return $contract->save();
    }

}