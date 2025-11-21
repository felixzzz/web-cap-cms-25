<?php

namespace App\Domains\PostCategory\Http\Requests\Backend;

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
            'type' => [],
            'name' => ['required', 'max:255', 'string'],
            'description' => ['string', 'max:255', 'nullable'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ];
    }
}
