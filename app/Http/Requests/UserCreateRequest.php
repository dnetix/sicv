<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'document' => 'required',
            'mobile' => 'required',
            'expedition_city' => 'required',
            'document_type' => 'string',
            'city' => 'string',
            'address' => 'string',
            'phone_number' => 'string',
            'email' => 'email',
        ];
    }
}
