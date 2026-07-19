<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            // Same constraints the legacy app enforced client-side: at least
            // 3 characters, no dots (documents are typed without separators).
            'document_number' => ['required', 'string', 'min:3', 'max:25', 'regex:/^[^.]+$/', Rule::unique('clients')],
            'document_type' => ['required', Rule::in(['CC', 'CE'])],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'document_issue_place' => ['required', 'string', 'max:45'],
            'city' => ['required', 'string', 'min:3', 'max:45'],
            'address' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:80'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'document_number' => 'documento',
            'document_type' => 'tipo de documento',
            'name' => 'nombre',
            'document_issue_place' => 'lugar de expedición',
            'city' => 'ciudad',
            'address' => 'dirección',
            'phone' => 'teléfono',
            'mobile' => 'celular',
            'email' => 'correo electrónico',
        ];
    }
}
