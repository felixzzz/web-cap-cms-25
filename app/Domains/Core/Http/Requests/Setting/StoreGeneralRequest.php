<?php

namespace App\Domains\Core\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralRequest extends FormRequest
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
            'app' => ['required', 'array'],
            'app.name' => ['required', 'string'],
            'app.tagline' => ['required', 'string'],
            'app.mail.admin' => ['required', 'email'],
            'app.register.admin' => ['integer', 'min:1', 'nullable'],
            'app.register.user' => ['integer', 'min:1', 'nullable'],
            'app.meta_title' => ['required', 'string'],
            'app.meta_description' => ['nullable', 'string'],
            'app.meta_keywords' => ['nullable', 'string'],
        ];
    }
}
