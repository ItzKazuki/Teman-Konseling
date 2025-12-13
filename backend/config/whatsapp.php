<?php

return [
    'fonnte' => [
        'api' => env('WHATSAPP_FONNTE_API', ''),
        'token' => env('WHATSAPP_FONNTE_TOKEN'),
    ],

    'wapi' => [
        'api' => env('WHATSAPP_WAPI_API'),
        'auth' => env('WHATSAPP_WAPI_AUTH'),
        'country_code' => env('WHATSAPP_WAPI_COUNTRY_CODE', '62'),
    ],

    'gateway' => env('WHATSAPP_GATEWAY_DRIVER', 'fonnte'),
];
