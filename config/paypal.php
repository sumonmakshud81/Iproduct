<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => "live", // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => '',
        'password'    => '',
        'secret'      => '',
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
        'app_id'      => 'APP-80W284485P519543T',    // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => '',
        'password'    => '',
        'secret'      => '',
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id'      => '',         // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can Only Be 'Sale', 'Authorization', 'Order'
    'currency'       => 'USD',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'invoice_prefix' => env('PAYPAL_INVOICE_PREFIX', 'PAYPALDEMOAPP'),
];
