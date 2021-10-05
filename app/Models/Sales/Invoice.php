<?php

namespace App\Models\Sales;

use App\Models\Clients\Client;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice.
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $client_id
 * @property int $user_id
 * @property int $amount
 */
class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'created_at',
        'updated_at',
        'client_id',
        'user_id',
        'amount',
    ];

    public function id()
    {
        return $this->id;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function updatedAt()
    {
        return $this->updated_at;
    }

    public function clientId()
    {
        return $this->client_id;
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
        return $this;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['amount', 'id']);
    }
}
