<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['document_number', 'document_type', 'name', 'document_issue_place', 'address', 'phone', 'mobile', 'email', 'city'])]
class Client extends Model
{
    use HasFactory;

    /**
     * @return HasMany<Contract, $this>
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * @return HasMany<Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * @return HasMany<ClientNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(ClientNote::class)->latest();
    }

    /**
     * The JSON shape shared by the live-search endpoint, the quick-create
     * response and the new-contract prefill: enough to render the selected
     * client chip and its notes.
     *
     * Requires `notes.user` loaded and `contracts_count` counted to avoid
     * lazy queries per client.
     *
     * @return array<string, mixed>
     */
    public function searchPayload(): array
    {
        return [
            'id' => $this->id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'name' => $this->name,
            'phone' => $this->phone ?: $this->mobile,
            'address' => $this->address,
            'city' => $this->city,
            'contracts_count' => (int) ($this->contracts_count ?? 0),
            'url' => route('clients.show', $this),
            'notes' => $this->notes->map(fn (ClientNote $note) => [
                'body' => $note->body,
                'severity' => $note->severity->value,
                'author' => $note->user->name,
                'date' => $note->created_at->format('d/m/Y'),
            ])->all(),
        ];
    }
}
