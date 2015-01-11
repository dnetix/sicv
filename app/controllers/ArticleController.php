<?php

use SICV\Articles\Actions\UpdateArticleLocationCommand;
use SICV\Articles\ArticleRepository;
use SICV\Core\Commander\CommandBus;

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

}
