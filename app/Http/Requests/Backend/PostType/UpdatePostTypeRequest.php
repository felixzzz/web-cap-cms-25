<?php

namespace App\Http\Requests\Backend\PostType;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() ? true : false;
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
            'is_public' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'is_tags' => ['boolean'],
            'is_category' => ['boolean'],
            'is_content' => ['boolean'],
            'featured' => ['boolean'],
            'featured_image' => ['boolean'],
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
            'show_in_menu' => $this->toBoolean($this->show_in_menu),
            'is_public' => $this->toBoolean($this->is_public),
            'is_category' => $this->toBoolean($this->is_category),
            'is_tags' => $this->toBoolean($this->is_tags),
            'is_content' => $this->toBoolean($this->is_content),
            'featured' => $this->toBoolean($this->featured),
            'featured_image' => $this->toBoolean($this->featured_image),
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
