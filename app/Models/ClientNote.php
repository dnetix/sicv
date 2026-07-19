<?php

namespace App\Models;

use App\Enums\ClientNoteSeverity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Operator-entered flag on a client ("left their physical ID with us",
 * "did not pay back contract X") surfaced wherever the client is selected,
 * most importantly on the new-contract screen.
 */
#[Fillable(['body', 'severity', 'user_id'])]
class ClientNote extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'severity' => ClientNoteSeverity::class,
        ];
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
