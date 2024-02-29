<?php

namespace App\Http\Requests\Auth;

use App\Rules\GoogleReCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
		return [
			'email' => ['required', 'string', 'email'],
			'password' => ['required', 'string'],
            'captcha' => ['required', 'string', new GoogleReCaptcha()]
		];
	}

	public function messages(): array
    {
		return [
			'email.required' => 'Вы должны указать почту.',
			'email.string' => 'Вы должны указать корректную почту.',
			'email.email' => 'Вы должны указать корректную почту.',
			'password.required' => 'Вы должны указать пароль.',
            'captcha.required' => 'Токен проверки на робота неверен, обновите страницу.',
            'captcha.string' => 'Токен должен быть в виде строки.'
		];
	}
}
