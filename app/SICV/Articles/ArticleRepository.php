<?php  namespace SICV\Articles;

use SICV\Utils\LineageModel\LineageTree;

class ArticleRepository {

    public function getArticleTypesAsLineageTree(){
        $articleTypes = $this->getArticleTypes();

        $lineageArticleTypes = new LineageTree();
        foreach($articleTypes as $articleType){
            $lineageArticleTypes->addNode($articleType->id, $articleType->article_type_id, $articleType);
        }
        return $lineageArticleTypes;
    }

    public function getArticleTypes(){
        return ArticleType::all();
    }

    public function getArticleById($id) {
        return Article::findOrFail($id);
    }

    public function create(Article &$article) {
        return $article->save();
    }

    public function update(Article $article) {
        return $article->save();
    }

}