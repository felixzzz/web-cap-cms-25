<?php

namespace App\Http\Requests\Backend\Form;

use App\Traits\GateRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
{

    use GateRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizePermission('admin.access.form-fields.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string'],
            'label' => ['required', 'string'],
            'input' => ['nullable', 'string'],
            'placeholder' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable'],
            'class' => ['nullable', 'string']
        ];
    }
}
