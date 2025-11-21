<?php

namespace App\Http\Requests\Backend\Form;

use App\Traits\GateRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    use GateRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizePermission('admin.access.forms.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'auto_reply' => ['boolean', 'nullable'],
            'admin_email' => ['nullable', 'email'],
            'subject' => ['nullable', 'string'],
            'message' => ['string', 'nullable'],
        ];
    }

}
