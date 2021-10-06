<?php

namespace App\Models\Contracts;

use App\Presenters\ExtensionPresenter;
use App\Models\Utils\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    use PresentableTrait;

    protected $presenter = ExtensionPresenter::class;

    protected $table = 'extensions';
    protected $fillable = [
        'user_id',
        'contract_id',
        'amount',
        'created_at',
    ];

    public function id()
    {
        return $this->id;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function contractId()
    {
        return $this->contract_id;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
