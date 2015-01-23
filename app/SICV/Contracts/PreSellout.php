<?php  namespace SICV\Contracts;

use Eloquent;

/**
 * Class PreSellout
 * @property integer $contract_id
 * @package SICV\Contracts
 */
class PreSellout extends Eloquent {

    protected $table = 'pre_sellouts';
    protected $fillable = ['contract_id'];

    public $timestamps = false;

    public function contractId(){
        return $this->contract_id;
    }

    public function setContractId($contractId){
        $this->contract_id = $contractId;
    }

    /* ----------- Relationships --------------- */

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

}