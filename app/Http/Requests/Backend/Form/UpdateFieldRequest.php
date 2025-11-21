<?php

namespace App\Http\Requests\Backend\Form;

use App\Traits\GateRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
{
    use GateRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizePermission('admin.access.form-fields.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'label' => ['required', 'string'],
            'input' => ['nullable', 'string'],
            'placeholder' => ['nullable', 'string'],
            'is_required' => ['boolean'],
            'options' => ['nullable'],
            'class' => ['nullable', 'string']
        ];
    }

    /**
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_required' => $this->toBoolean($this->is_required),
        ]);
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
