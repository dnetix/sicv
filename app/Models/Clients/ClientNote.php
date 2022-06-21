<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contract;
use App\Models\Users\User;
use App\Models\Utils\Presenters\PresentableTrait;
use App\Presenters\ClientNotePresenter;
use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    use PresentableTrait;

    public const LEVEL_INFO = 'info';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_CRITICAL = 'critical';
    public const LEVEL_ALERT = 'alert';

    public static array $LEVELS = [
        self:: LEVEL_INFO,
        self:: LEVEL_WARNING,
        self:: LEVEL_CRITICAL,
        self:: LEVEL_ALERT,
    ];

    protected $presenter = ClientNotePresenter::class;

    protected $table = 'client_notes';

    protected $fillable = [
        'note',
        'client_id',
        'user_id',
        'contract_id',
        'importance',
    ];

    public function id()
    {
        return $this->id;
    }

    public function note()
    {
        return $this->note;
    }

    public function clientId()
    {
        return $this->client_id;
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function contractId()
    {
        return $this->contract_id;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function importance()
    {
        return $this->importance;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
