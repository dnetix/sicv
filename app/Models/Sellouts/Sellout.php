<?php

namespace App\Models\Sellouts;

use App\Models\Contracts\Contract;
use Illuminate\Database\Eloquent\Model;

class Sellout extends Model
{
    protected $table = 'sellouts';
    protected $fillable = [
        'note',
        'user_id',
        'gold_weight',
    ];

    public function id()
    {
        return $this->id;
    }

    public function note()
    {
        return $this->note();
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function goldWeight()
    {
        return $this->gold_weight;
    }

    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    public function setGoldWeight($goldWeight)
    {
        $this->gold_weight = $goldWeight;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function contracts()
    {
        return $this->belongsToMany(Contract::class);
    }
}
