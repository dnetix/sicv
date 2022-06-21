<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required',
            'months' => 'required|numeric|gt:0',
            'percentage' => 'required|numeric|gt:0',
        ];
    }

    public function articles(): array
    {
        $data = $this->all();
        $articles = [];
        foreach ($data['description'] as $index => $description) {
            $articles[] = [
                'description' => $data['description'][$index],
                'weight' => $data['weight'][$index],
                'amount' => (float)str_replace(',', '', $data['amount'][$index]),
                'article_type_id' => (int)$data['article_type_id'][$index],
            ];
        }
        return $articles;
    }
}
