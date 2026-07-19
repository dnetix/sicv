<?php

namespace App\Http\Requests;

use App\Models\ItemType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
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
            'client_id' => ['required', Rule::exists('clients', 'id')],
            'description' => ['required', 'string'],
            'item_type_id' => ['required', Rule::exists('item_types', 'id')],
            // The legacy app only enforced the gold weight in the browser;
            // it is now a server rule.
            'weight_grams' => [
                Rule::requiredIf(fn () => (int) $this->input('item_type_id') === ItemType::GOLD),
                'nullable', 'numeric', 'gt:0',
            ],
            'amount' => ['required', 'integer', 'min:1'],
            'monthly_rate' => ['required', 'numeric', 'gt:0', 'max:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:24'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'cliente',
            'description' => 'artículo',
            'item_type_id' => 'tipo de artículo',
            'weight_grams' => 'peso',
            'amount' => 'valor',
            'monthly_rate' => 'porcentaje',
            'term_months' => 'número de meses',
        ];
    }
}
