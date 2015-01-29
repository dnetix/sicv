<?php

use SICV\Articles\Actions\CreateOrRetrieveArticleCommand;
use SICV\Articles\ArticleRepository;
use SICV\Clients\ClientRepository;
use SICV\Contracts\Actions\AnnulContractCommand;
use SICV\Contracts\Actions\TerminateContractCommand;
use SICV\Core\Commander\CommandBus;
use SICV\Contracts\Actions\CreateNewContractCommand;
use SICV\Contracts\Actions\SaveNewExtensionCommand;
use SICV\Contracts\ContractRepository;
use SICV\Core\Validations\FormValidationException;
use SICV\Utils\Hierachical\CategoriesTree;

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
        $articleTypes = $this->articleRepository->getArticleTypes();
        $data['articleTypes'] = CategoriesTree::load($articleTypes);
        $data['default_months'] = Config::get('sicv.default_months');
        $data['default_percentage'] = Config::get('sicv.default_percentage');

        return View::make('contract.contract_new', $data);
    }

    public function copy($contract_id){
        $contract = $this->contractRepository->getContractById($contract_id);
        //TODO Refactor within the repositories
        $data['contract'] =& $contract;
        $data['client'] =& $contract->client;
        $data['articles'] =& $contract->articles;
        $articleTypes = $this->articleRepository->getArticleTypes();
        $data['articleTypes'] = CategoriesTree::load($articleTypes);

        return View::make('contract.contract_new', $data);
    }

    public function store(){
        //TODO Refactor and a LOT!

        // Obtiene los articulos y los crea en caso de que no existan ya?
        $articlesInformation = Input::only(['description', 'weight', 'article_type_id', 'article_id']);
        $amounts = Input::get('article_amount');

        $articlesWithAmount = [];
        $contractAmount = 0;

        foreach($amounts as $index => $amount){
            if(!empty($articlesInformation['description'][$index])) {
                $article = $this->execute(
                    new CreateOrRetrieveArticleCommand([
                        'description' => $articlesInformation['description'][$index],
                        'weight' => $articlesInformation['weight'][$index],
                        'article_type_id' => $articlesInformation['article_type_id'][$index],
                        'possible_id' => $articlesInformation['article_id'][$index]
                    ])
                );

                $amount = $this->normalizeAmount($amounts[$index]);
                $contractAmount += $amount;
                $articlesWithAmount[] = compact('article', 'amount');
            }
        }

        if(sizeof($articlesWithAmount) == 0){
            Flash::error("WTF are you doing?");
            return Redirect::to('user.dashboard');
        }

        $command = (new CreateNewContractCommand(
            Auth::id(), Input::get('client_id'), Input::get('months'), Input::get('percentage'), $contractAmount
        ))->setArticles($articlesWithAmount);

        $this->execute($command);

        Flash::overlay()->info('Se ha guardado el contrato');
        return Redirect::route('user.dashboard');

    }

    public function view($id){
        $contract = $this->contractRepository->getContractById($id);

        $data['contract'] =& $contract;
        $data['articles'] = $contract->articles;
        $data['client'] = $contract->client;
        $data['extensions'] = $contract->extensions;
        $data['clientNotes'] = $this->clientRepository->getClientNotes($data['client'], $contract->id());

        if($contract->isAnnulled()){
            $data['annul'] = $contract->annul;
        }else if($contract->isEnded()){
            $data['products'] = $contract->products;
            $data['sellout'] = $contract->sellout->first();
        }

        return View::make('contract.contract_view', $data);
    }

    public function annul($id = null){
        if(is_null($id)){
            $id = Input::get('id');
        }
        
        $data = [
            'created_at' => Date::create()->toSQLTimestamp(),
            'note' => Input::get('note'),
            'password' => Input::get('password'),
            'contract_id' => $id,
            'user_id' => Auth::id()
        ];
        
        $command = new AnnulContractCommand($data);

        try {
            $annul = $this->execute($command);
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return Redirect::back();
        }

        Flash::overlay()->info("Ha anulado el contrato");
        return Redirect::route('contract.view', $annul->contractId());

    }

    public function extension(){
        $command = new SaveNewExtensionCommand();
        $command->mapInputData(
            Auth::id(),
            Input::get('contract_id'),
            Input::get('amount')
        );

        try {
            $extension = $this->execute($command);
        } catch (FormValidationException $e) {
            Flash::overlay()->error($e->getErrors()->all());
            return Redirect::back()->withInput();
        }

        Flash::overlay()->info('Se ha guardado el nuevo abono');
        return Redirect::route('contract.view', $extension->contractId());
    }

    public function terminate(){
        $command = new TerminateContractCommand();
        $command->setCommandValues(Input::get('id'), Input::get('amount'));
        $this->execute($command);

        Flash::overlay()->info("Se ha cancelado el contrato exitosamente");
        return Redirect::route('user.dashboard');
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
