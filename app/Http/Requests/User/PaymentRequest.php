<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'coins' => ['required', 'integer', 'min:25'],
            'payment_system' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'coins.min' => 'Минимальная сумма пополнения 25 монета.',
            'coins' => 'Сумма пополнения указана неверно, повторите попытку.',
            'payment_system' => 'Система оплаты выбрана неверно, повторите попытку'
        ];
    }
}
