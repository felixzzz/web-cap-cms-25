<?php

namespace App\Domains\Core\Http\Requests\SidebarMenu;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required',
            'parent_id' => 'nullable',
            'url' => 'nullable',
            'icon' => 'nullable',
            'order' => 'nullable',
            'is_active' => 'nullable',
            'permissions' => 'nullable',
        ];
    }
}
