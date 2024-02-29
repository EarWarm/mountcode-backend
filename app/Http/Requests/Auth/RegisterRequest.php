<?php

namespace App\Http\Requests\Auth;

use App\Rules\GoogleReCaptcha;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::default()],
            'captcha' => ['required', 'string', new GoogleReCaptcha()]
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Вы должны указать почту.',
            'email.email' => 'Вы должны указать корректную почту.',
            'email.unique' => 'Почта уже используется другим аккаунтом.',
            'password.required' => 'Вы должны указать пароль.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.min' => 'Пароль не может быть меньше 6 символов.',
            'password.max' => 'Пароль не может быть больше 32-х символов.',
            'captcha.required' => 'Токен проверки на робота неверен, обновите страницу.',
            'captcha.string' => 'Токен должен быть в виде строки.'
        ];
    }
}
