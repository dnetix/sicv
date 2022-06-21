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
            'client_id' => 'required|numeric',
            'months' => 'required|numeric|gt:0',
            'percentage' => 'required|numeric|gt:0',
        ];
    }

    public function clientId(): int
    {
        return (int)$this->get('client_id');
    }

    public function months()
    {
        return $this->get('months');
    }

    public function percentage()
    {
        return $this->get('percentage');
    }

    public function note(): array
    {
        if (empty($this->get('note'))) {
            return [];
        }

        return [
            'note' => $this->get('note'),
            'importance' => $this->get('importance'),
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
