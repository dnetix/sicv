<?php

namespace App\Models\Contracts;

use App\Models\Users\User;
use App\Models\Utils\Presenters\PresentableTrait;
use App\Presenters\AnnulPresenter;
use Illuminate\Database\Eloquent\Model;

class Annul extends Model
{
    use PresentableTrait;

    protected $presenter = AnnulPresenter::class;

    protected $table = 'annuls';
    protected $fillable = [
        'created_at',
        'note',
        'original_amount',
        'contract_id',
        'user_id',
    ];
    public $timestamps = false;

    public function createdAt()
    {
        return $this->created_at;
    }

    public function note()
    {
        return $this->note;
    }

    public function originalAmount()
    {
        return $this->original_amount;
    }

    public function contractId()
    {
        return $this->contract_id;
    }

    public function userId()
    {
        return $this->user_id;
    }

    /* ----------- Relationships --------------- */

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
