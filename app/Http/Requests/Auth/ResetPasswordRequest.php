<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'token' => ['required', 'string'],
			'email' => ['required', 'email', 'exists:password_reset_tokens,email'],
			'password' => ['required', 'confirmed', Password::default()],
		];
	}

	public function messages(): array
    {
		return [
			'email.required' => 'Вы должны указать почту.',
			'email.email' => 'Вы должны указать корректную почту.',
			'email.exists' => 'Токен восстановления неверен для данной почты.',
			'token.exists' => 'Токен восстановления неверен.',
			'token.required' => 'Токен восстановления не был передан.',
			'token.string' => 'Токен восстановления имеет неверный формат.',
			'password.required' => 'Вы должны указать пароль.',
			'password.confirmed' => 'Пароли не совпадают.',
			'password.min' => 'Пароль не может быть меньше 6 символов.',
		];
	}
}
