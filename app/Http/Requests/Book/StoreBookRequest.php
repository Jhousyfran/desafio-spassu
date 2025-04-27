<?php

namespace App\Http\Requests\Book;

use Illuminate\Validation\Rule;

class StoreBookRequest extends BookRequest
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
            'title' => 'required|string|max:400|min:3|unique:books,title',
            'subtitle' => 'string|max:250',
            'publisher' => 'required|string|max:40|min:3',
            'year_of_publication' => 'required|integer|min:1800|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'edition' => 'required|numeric|min:1',
            'authors' => 'required|array',
            'authors.*' => [
                'required',
                'integer',
                Rule::exists('authors', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
            'topics' => 'required|array',
            'topics.*' => [
                'required',
                'integer',
                Rule::exists('topics', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }
}
