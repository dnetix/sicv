<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'document' => 'required|regex:/^\d+$/',
            'mobile' => 'required|string',
            'expedition_city' => 'required',
            'document_type' => 'string',
            'city' => 'string|nullable',
            'address' => 'string|nullable',
            'phone_number' => 'string|nullable',
            'email' => 'email|nullable',
        ];
    }
}
