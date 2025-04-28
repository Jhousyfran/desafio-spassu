<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTopicRequest extends TopicRequest
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
            // 'name' => 'required|string|max:20|min:3|unique:topics,name,' . $this->route('topic'),
            'name' => [
                'required',
                'string',
                'max:20',
                'min:3',
                Rule::unique('topics', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })->ignore($this->route('topic')),
            ],
        ];
    }
}
