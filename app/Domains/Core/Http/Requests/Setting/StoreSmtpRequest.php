<?php

namespace App\Domains\Core\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmtpRequest extends FormRequest
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
            'mail' => ['required'],
            'mail.mailers.smtp.host' => ['required'],
            'mail.mailers.smtp.port' => ['required', 'numeric'],
            'mail.mailers.smtp.encryption' => ['required', 'in:ssl,tls,starttls'],
            'mail.mailers.smtp.username' => ['string', 'nullable'],
            'mail.mailers.smtp.password' => ['string', 'nullable'],
            'mail.from.address' => ['required', 'email'],
            'mail.from.name' => ['required', 'string'],
        ];
    }
}
