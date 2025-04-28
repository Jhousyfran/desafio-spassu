<?php

namespace App\Http\Requests\Topic;

use App\Models\Topic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTopicRequest extends TopicRequest
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
            // 'name' => 'required|string|max:20|min:3|unique:topics,name',
            'name' => [
                'required',
                'string',
                'max:20',
                'min:3',
                Rule::unique('topics', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => Topic::formatField($this->name),
        ]);
    }


}
