<?php

return [
    'general'        => [
        'name'    => env('APP_NAME'),
        'email'   => env('APP_EMAIL'),
        'phone'   => env('APP_PHONE'),
        'version' => env('APP_VERSION'),
        'sms'     => env('APP_SMS'),
    ],

    'mpesa-kopokopo' => [
        'env'       => env('MPESA_KOPOKOPO_ENV', 'sandbox'),
        'type'      => env('MPESA_KOPOKOPO_TYPE', 4),
        'shortcode' => env('MPESA_KOPOKOPO_SHORTCODE', '174379'),
        'key'       => env('MPESA_KOPOKOPO_KEY', 'l6jE7kgV4lCtNH4aveMueR9QdGkbutfR'),
        'username'  => env('MPESA_KOPOKOPO_USERNAME', ''),
    ],

    'mpesa-c2b'      => [
        'env'       => env('MPESA_C2B_ENV', 'sandbox'),
        'type'      => env('MPESA_C2B_TYPE', 4),
        'shortcode' => env('MPESA_C2B_SHORTCODE', '174379'),
        'key'       => env('MPESA_C2B_KEY', 'l6jE7kgV4lCtNH4aveMueR9QdGkbutfR'),
        'secret'    => env('MPESA_C2B_SECRET', '5slRuAafb4Gk7Ogo'),
        'username'  => env('MPESA_C2B_USERNAME', ''),
        'passkey'   => env('MPESA_C2B_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),
    ],

    'mpesa-b2c'      => [
        'env'       => env('MPESA_B2C_ENV', 'sandbox'),
        'type'      => env('MPESA_B2C_TYPE', 4),
        'shortcode' => env('MPESA_B2C_SHORTCODE', '174379'),
        'key'       => env('MPESA_B2C_KEY', 'l6jE7kgV4lCtNH4aveMueR9QdGkbutfR'),
        'secret'    => env('MPESA_B2C_SECRET', '5slRuAafb4Gk7Ogo'),
        'username'  => env('MPESA_B2C_USERNAME', ''),
        'passkey'   => env('MPESA_B2C_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),
    ],
];
