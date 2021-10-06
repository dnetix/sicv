<?php

namespace App\Models\Contracts\Actions;

use App\Models\Contracts\Contract;
use App\Models\Core\Commander\CommandHandler;
use App\Repositories\ContractRepository;

class CreateNewContractCommandHandler implements CommandHandler
{
    /**
     * @var ContractRepository
     */
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function handle($command)
    {
        $contract = new Contract();

        $contract->fill($command->toAttributes());
        $contract->toActive();

        $this->contractRepository->save($contract);
        $this->contractRepository->associateWithArticles($contract, $command->articlesWithAmount());

        return $contract;
    }
}
