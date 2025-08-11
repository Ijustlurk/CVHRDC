<?php

return [
    'sitekey' => env('RECAPTCHA_SITE_KEY'),
    'secret'  => env('RECAPTCHA_SECRET_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
