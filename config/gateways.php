<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'kuickpay' => [
        'token_prefix' => '11570',
        'token_ref' => '800',
        'UserName' => 'SKILLSRATORADMIN',
        'Pass' => 'SKILLS@123',
    ],
    'stripe' => [
        'stripe_key' => env('STRIPE_KEY',''),
        'stripe_secret' => env('STRIPE_SECRET',''),
    ],
    // 'kuickpay' => [
    //     'token_prefix' => '06880',
    //     'token_ref' => '800',
    //     'UserName' => 'excom',
    //     'Pass' => 'excom123',
    // ]
];
