<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'auth' => [
        'login' => [
            'success' => 'Login successful',
            'failed' => 'Failed to login',
            'blocked' => 'Your account has been locked',
            'wait_verify' => 'Please verify your account',
        ],
        'logout' => [
            'success' => 'Logout successful'
        ]
    ],
    'teacher' => [
        'register' => [
            'has_been' => 'Has been registered'
        ]
    ],
    'bad_request' => 'Bad request',
    'server_error' => 'Server error',
    'not_found' => 'Not found',
    'forbidden' => 'Unauthenticated.',
    'update_successful' => 'Update successful',
    'import_successful' => 'Import successful',
    'moto' => [
        'images' => [
            'max' => 'Quantity image than maximum(' . config('define.images.moto.max') . ')',
        ],
        'rent' => [
            'failed' => 'Reset password failed',
            'has_been_scheduled' => 'Motos has been scheduled.'
        ]
    ],
    'params' => [
        'invalid' => 'There are some invalid params'
    ],
    'order_issue' => 'Have :quantity order issues',
];
