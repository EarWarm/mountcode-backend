<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductListRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'category_id' => ['integer', 'min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'page' => 'Неверный формат номера страницы, попробуй обновить страницу!',
            'category_id' => 'Неверная категория, попробуйте обновить страницу!'
        ];
    }
}
