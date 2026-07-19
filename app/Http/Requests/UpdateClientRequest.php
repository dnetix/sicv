<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * The document number is immutable: it is printed on legal contracts.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', Rule::in(['CC', 'CE'])],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'document_issue_place' => ['required', 'string', 'max:45'],
            'city' => ['nullable', 'string', 'max:45'],
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
        return (new StoreClientRequest)->attributes();
    }
}
