<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PreSellout.
 * @property int $contract_id
 */
class PreSellout extends Model
{
    protected $table = 'pre_sellouts';
    protected $fillable = ['contract_id'];

    public $timestamps = false;

    public function contractId()
    {
        return $this->contract_id;
    }

    public function setContractId($contractId)
    {
        $this->contract_id = $contractId;
    }

    /* ----------- Relationships --------------- */

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
