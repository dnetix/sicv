<?php

use SICV\Articles\Actions\UpdateArticleLocationCommand;
use SICV\Articles\ArticleRepository;
use SICV\Articles\ArticleType;
use SICV\Core\Commander\CommandBus;
use SICV\Utils\Hierachical\CategoriesTree;

class ArticleController extends BaseController {

    private $articleRepository;

    function __construct(ArticleRepository $articleRepository, CommandBus $commandBus) {
        $this->articleRepository = $articleRepository;
        parent::__construct($commandBus);
    }

    public function updateLocation($id = null){
        if(is_null($id)){
            $id = Input::get('id');
        }

        $data['isOk'] = true;

        try {
            $command = new UpdateArticleLocationCommand($id, Input::get('location'));
            $article = $this->execute($command);
            $data['location'] = $article->location();
        } catch (Exception $e) {
            $data['isOk'] = false;
            $data['error'] = $e->getMessage();
        }

        return $data;
    }

    public function createOrUpdateArticleType(){
        if(!empty(Input::get('id'))){
            $articleType = $this->articleRepository->getArticleTypeById(Input::get('id'));
        }else{
            $articleType = new ArticleType();
        }

        $articleType->fill([
            'article_type' => (empty(Input::get('name')) ? null : Input::get('name')),
            'article_type_id' => (empty(Input::get('parent_id')) ? null : Input::get('parent_id'))
        ]);

        $this->articleRepository->saveArticleType($articleType);

        return Redirect::route('article.types');

    }

    public function articletype($id = null){
        if(is_null($id)){
            $id = Input::get('id');
        }
        $articleType = $this->articleRepository->getArticleTypeById($id);
        return $articleType;
    }

    public function articletypes(){

        $articleTypes = $this->articleRepository->getArticleTypes();

        $data['articleTypes'] = CategoriesTree::load($articleTypes);

        return View::make('config.article_types', $data);
    }

}
