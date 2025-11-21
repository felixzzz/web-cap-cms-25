<?php

namespace App\Domains\Auth\Http\Requests\Frontend\Auth;

use App\Domains\Auth\Rules\UnusedPassword;
use App\Rules\ValidCurrentPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class UpdatePasswordRequest.
 */
class UpdatePasswordRequest extends FormRequest
{
    protected $redirect = '/account#password';

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
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => ['required', 'max:100', new ValidCurrentPassword()],
            'password' => array_merge(
                [
                    'max:100',
//                    new UnusedPassword((int) $this->segment(4)),
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
                PasswordRules::changePassword(
                    $this->email,
                    config('boilerplate.access.user.password_history') ? 'current_password' : null
                )
            ),
            'password_confirmation' => ['required', 'same:password'],
        ];
    }
}
