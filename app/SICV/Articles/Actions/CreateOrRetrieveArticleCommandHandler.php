<?php  namespace SICV\Articles\Actions;

use SICV\Articles\Article;
use SICV\Articles\ArticleRepository;
use SICV\Commander\CommandHandler;
use SICV\Commander\Eventing\EventGenerator;

class CreateOrRetrieveArticleCommandHandler implements CommandHandler {

    use EventGenerator;


    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function handle($command) {

        if(!is_null($command->possible_id)){
            try{
                $article = $this->getArticleById($command);
                if($article->description() == $command->description){
                    return $article;
                }
            }catch (\Exception $e){

            }
        }

        $article = new Article();
        $this->fillArticleFields($article, $command);
        $this->saveArticle($article);
        return $article;

    }

    private function fillArticleFields(&$article, $command) {
        $article->fill((array) $command);
    }

    /**
     * @param $command
     * @return \Illuminate\Support\Collection|static
     */
    protected function getArticleById($command) {
        return $this->articleRepository->getArticleById($command->possible_id);
    }

    private function saveArticle(&$article) {
        $this->articleRepository->create($article);
    }
}