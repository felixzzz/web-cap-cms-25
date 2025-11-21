<?php

namespace App\Domains\Document\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'template' => [],
            'category' => [],
            'category_id' => [],
            'page' => ['required', 'string'], // Add any specific validation for 'page' if needed
            'section' => ['nullable', 'string'], // Add any specific validation for 'section' if needed
            'document_name_en' => ['required', 'max:255', 'string'],
            'document_name_id' => ['required', 'max:255', 'string'],
            'document_type' => ['string'], // Validate the document type
            'published_at' => ['nullable', 'date'], // Validate if it's a valid date or null
            'document_file_id' => [], // Assuming you're storing file IDs
            'document_file_en' => [], // Assuming 'id' and 'en' are the only valid language codes
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'release_year' => 'nullable|integer',
            'pages' => 'nullable|integer',
            'format' => 'nullable|string|max:50',
            'alt_image' => ['nullable'],
            'alt_image_en' => ['nullable'],
        ];
    }
}
