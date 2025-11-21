<?php

namespace App\Domains\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'post_type_id' => ['nullable', 'exists:post_types,id']
        ];
    }
}
