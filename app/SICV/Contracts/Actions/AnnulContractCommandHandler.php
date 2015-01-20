<?php  namespace SICV\Contracts\Actions;

use SICV\Contracts\Annul;
use SICV\Contracts\ContractRepository;
use SICV\Contracts\ContractStates;
use SICV\Core\Commander\CommandHandler;
use SICV\Users\Exceptions\UnauthorizedUserAction;
use SICV\Users\UserRepository;

class AnnulContractCommandHandler implements CommandHandler {

    private $contractRepository;
    private $userRepository;

    function __construct(UserRepository $userRepository, ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
        $this->userRepository = $userRepository;
    }

    public function handle($command) {

        if($this->validateUserPassword($command->user_id, $command->password)){
            $contract = $this->getContractById($command->contract_id);

            if($contract->payedExtensions() > 0){
                throw new \Exception("Ya se han aceptado abonos para este contrato");
            }

            $annul = new Annul();
            $annul->created_at = $command->created_at;
            $annul->note = $command->note;
            $annul->original_amount = $contract->amount();
            $annul->contract_id = $contract->id();
            $annul->user_id = $command->user_id;

            $this->contractRepository->saveAnnul($annul);

            $contract->amount = 0;
            $contract->state = ContractStates::ANNULLED;
            $this->contractRepository->update($contract);

            return $annul;

        }else{
            throw new UnauthorizedUserAction("No ha ingresado una contraseña valida para el usuario");
        }

    }

    public function getContractById($id) {
        return $this->contractRepository->getContractById($id);
    }

    private function validateUserPassword($user_id, $password) {
        return $this->userRepository->validateUserPassword($user_id, $password);
    }

}