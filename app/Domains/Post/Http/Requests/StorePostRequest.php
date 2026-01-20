<?php

namespace App\Domains\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => ['required_without:title_en', 'max:200'],
            'slug' => 'max:100',
            'title_en' => ['required_without:title', 'max:200'],
            'slug_en' => 'max:100',
            'excerpt' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'content_en' => ['nullable'],
            'tags' => ['nullable'],
            'tags_id' => ['nullable'],
            'categories' => ['array', 'required', 'exists:categories,id'],
            'featured_image' => ['nullable'],
            'alt_image' => ['nullable'],
            'alt_image_en' => ['nullable'],
            'featured_image_remove' => ['nullable'],
            'meta_title' => ['nullable'],
            'meta_keyword' => ['nullable'],
            'meta_description' => ['nullable'],
            'featured' => ['nullable'],
            'status' => ['nullable'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
