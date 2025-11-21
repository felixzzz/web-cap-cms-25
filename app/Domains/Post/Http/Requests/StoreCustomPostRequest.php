<?php

namespace App\Domains\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomPostRequest extends FormRequest
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
            'name' => ['required'],
            'icon' => ['required'],
            'type' => ['required', 'in:post,page'],
            'is_public' => ['integer', 'min:1', 'nullable'],
            'show_in_menu' => ['integer', 'min:1', 'nullable'],
        ];
    }
}
