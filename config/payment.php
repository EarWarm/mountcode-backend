<?php

return [
    'freekassa' => [
        /*
         * Project`s id
         */
        'pay_url' => 'https://pay.freekassa.ru/',
        /*
         * Project`s id
         */
        'shop_id' => env('FREEKASSA_PROJECT_ID', ''),

        /*
         * First project`s secret key
         */
        'secret_word_1' => env('FREEKASSA_SECRET_WORD_1', ''),

        /*
         * Second project`s secret key
         */
        'secret_word_2' => env('FREEKASSA_SECRET_WORD_2', ''),

        /*
         * Currency
         */
        'currency' => 'RUB',

        /*
         * Lang
         */
        'lang' => 'ru'
    ]
];
