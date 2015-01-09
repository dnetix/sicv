<?php

use SICV\Articles\Actions\CreateOrRetrieveArticleCommand;
use SICV\Articles\ArticleRepository;
use SICV\Clients\Actions\EditClientInformationCommand;
use SICV\Clients\ClientRepository;
use SICV\Commander\CommandBus;
use SICV\Contracts\Actions\CreateNewContractCommand;
use SICV\Contracts\ContractRepository;

class ContractController extends BaseController {

    /**
     * @var ContractRepository
     */
    private $contractRepository;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    function __construct(ContractRepository $contractRepository, ClientRepository $clientRepository, ArticleRepository $articleRepository, CommandBus $commandBus) {
        $this->contractRepository = $contractRepository;
        $this->articleRepository = $articleRepository;
        $this->clientRepository = $clientRepository;
        parent::__construct($commandBus);
    }

    public function create($client_id = null){
        if(!is_null($client_id)){
            try {
                $data['client'] = $this->clientRepository->getClientById($client_id);
            } catch (Exception $e) {
                // Do nothing
            }
        }
        $data['articleTypes'] = $this->articleRepository->getArticleTypesAsLineageTree();

        return View::make('contract.contract_new', $data);
    }

    public function store(){
        //TODO Refactor and a LOT!

        $id = Input::get('client_id');
        $command = new EditClientInformationCommand($id, Input::all());
        $client = $this->execute($command);

        // Obtiene los articulos y los crea en caso de que no existan ya?
        $articlesInformation = Input::only(['article', 'weight', 'article_type_id', 'article_id']);
        $article_count = sizeof(Input::get('article'));

        $articles_id = [];

        for($i = 0; $i < $article_count; $i++){
            if(!empty($articlesInformation['article'])) {
                $articles_id[] = $this->execute(
                    new CreateOrRetrieveArticleCommand([
                            'description' => $articlesInformation['article'][$i],
                            'weight' => $articlesInformation['weight'][$i],
                            'article_type_id' => $articlesInformation['article_type_id'][$i],
                            'possible_id' => $articlesInformation['article_id'][$i]
                    ])
                );
            }
        }

        if(sizeof($articles_id) == 0){
            //TODO remove this
            Flash::error("WTF are you doing?");
            return Redirect::to('user.dashboard');
        }

        $createNewContractCommand = new CreateNewContractCommand();
        $createNewContractCommand->mapInputData(Input::all(), $client->getId(), Auth::id(), $articles_id);
        $this->execute($createNewContractCommand);

    }

    /**
     * Returns a panel HTML with the contracts of the day sended
     */
    public function contractsofday(){
        $day = Input::has('day') ? Input::get('day') : null;
        $data['contracts'] = $this->contractRepository->getContractsOfDay($day);
        $data['day'] = $day;
        return View::make('contract.partials._contracts_day_panel', $data);
    }

}
