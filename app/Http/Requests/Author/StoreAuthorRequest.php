<?php

namespace App\Http\Requests\Author;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuthorRequest extends AuthorRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => 'required|string|max:255|min:3|unique:authors,name',
            'name' => [
                'required',
                'string',
                'max:250',
                'min:3',
                Rule::unique('authors', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }


}
