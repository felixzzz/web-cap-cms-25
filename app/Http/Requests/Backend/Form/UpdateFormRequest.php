<?php

namespace App\Http\Requests\Backend\Form;

use App\Rules\BooleanRule;
use App\Traits\GateRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFormRequest extends FormRequest
{
    use GateRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizePermission('admin.access.forms.edit');
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
            'auto_reply' => ['boolean'],
            'admin_email' => ['nullable', 'email'],
            'subject' => ['nullable', 'string'],
            'message' => ['string', 'nullable'],
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
            'auto_reply' => $this->toBoolean($this->auto_reply),
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
