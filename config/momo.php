<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MoMo Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình thanh toán MoMo cho ứng dụng Laravel
    |
    */

    // Môi trường: 'sandbox' hoặc 'production'
    'environment' => env('MOMO_ENVIRONMENT', 'sandbox'),

    // Thông tin đối tác
    'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
    'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
    'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),

    // Endpoints
    'endpoints' => [
        'sandbox' => [
            'create' => 'https://test-payment.momo.vn/v2/gateway/api/create',
            'confirm' => 'https://test-payment.momo.vn/v2/gateway/api/confirm',
            'refund' => 'https://test-payment.momo.vn/v2/gateway/api/refund',
        ],
        'production' => [
            'create' => 'https://payment.momo.vn/v2/gateway/api/create',
            'confirm' => 'https://payment.momo.vn/v2/gateway/api/confirm',
            'refund' => 'https://payment.momo.vn/v2/gateway/api/refund',
        ],
    ],

    // Cấu hình mặc định
    'defaults' => [
        'request_type' => 'captureWallet',
        'lang' => 'vi',
        'currency' => 'VND',
    ],

    // Timeout cho HTTP requests (giây)
    'timeout' => env('MOMO_TIMEOUT', 30),

    // Logging
    'logging' => env('MOMO_LOGGING', true),
];
