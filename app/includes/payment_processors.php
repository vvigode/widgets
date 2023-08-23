<?php


return [
    'paypal' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fab fa-paypal',
    ],
    'stripe' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fab fa-stripe',
    ],
    'offline_payment' => [
        'payment_type' => ['one_time'],
        'icon' => 'fa fa-university',
    ],
    'coinbase' => [
        'payment_type' => ['one_time'],
        'icon' => 'fab fa-bitcoin',
    ],
    'payu' => [
        'payment_type' => ['one_time'],
        'icon' => 'fa fa-underline',
    ],
    'paystack' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fa fa-money-check',
    ],
    'razorpay' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fa fa-heart',
    ],
    'mollie' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fa fa-shopping-basket',
    ],
    'yookassa' => [
        'payment_type' => ['one_time'],
        'icon' => 'fa fa-ruble-sign',
    ],
];
