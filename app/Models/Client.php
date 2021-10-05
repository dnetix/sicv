<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'id_number',
        'id_type',
        'id_expedition',
        'address',
        'cell_number',
        'phone_number',
        'email',
        'city',
        'flagged',
    ];

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function idNumber(): string
    {
        return $this->id_number;
    }

    public function idType(): string
    {
        return $this->id_type;
    }

    public function idExpedition(): string
    {
        return $this->id_expedition;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function cellNumber(): string
    {
        return $this->cell_number;
    }

    public function phoneNumber(): string
    {
        return $this->phone_number;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function isFlagged(): bool
    {
        return $this->flagged == 1;
    }

    /* ----------- Relationships --------------- */

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class)->with('articles');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ClientNote::class);
    }
}
