<?php

namespace App\Domains\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
            'title' => ['required'],
            'content' => ['required'],
            'excerpt' => ['nullable'],
            'site_url' => ['nullable'],
            'meta_title' => ['nullable', 'string'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'exists:temporary_uploads,id'],
            'type' => ['in:page', 'required'],
            'status' => ['required', 'in:publish,draft,schedule'],
            'template' => ['required'],
            'published_at' => ['nullable'],
            'user_id' => ['required'],
            'pages_dynamic' => ['nullable'],
        ];
    }
}
