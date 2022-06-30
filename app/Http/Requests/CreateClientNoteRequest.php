<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientNoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'note' => 'required|string',
            'importance' => 'required|string',
        ];
    }

    public function note(): string
    {
        return $this->get('note');
    }

    public function importance(): string
    {
        return $this->get('importance');
    }
}
