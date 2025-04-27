<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'authors.*.exists' => 'Um dos autores selecionados não está disponível.',
            'topics.*.exists' => 'Um dos assuntos selecionados não está disponível.',
        ];
    }
}
