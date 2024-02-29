<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class GoogleReCaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $secretParams = '?secret=' . env('RECAPTCHA_V3_SECRET_KEY');
        $responseParams = '&response=' . $value;

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify' . $secretParams . $responseParams);
        return $response->json('success');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Проверка на робота не пройдена.';
    }
}
