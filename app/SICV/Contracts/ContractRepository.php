<?php  namespace SICV\Contracts;

class ContractRepository {

    public function create(&$contract) {
        $contract->save();
    }

    public function associateWithArticles(&$contract, $articles_id) {
        foreach($articles_id as $article_id){
            $contract->articles()->attach($article_id);
        }
    }
}