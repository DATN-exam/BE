<?php
return [
    'date_format' => 'Y-m-d',
    'datime_format' => 'Y-m-d H:i:s',
    'time_fomat' => 'H:i:s',
    'specified_age' => 12,
    'url_verify' => env('URL_FE', 'http://localhost:5175') . env('REGISTER_CONFIRM_REDIRECT', '/auth/verify'),
    'url_teacher' => env('URL_FE', 'http://localhost:5175') . env('TEACHER_REGISTER_CONFIRM_REDIRECT', '/teacher'),
    'url_exam' => env('URL_FE', 'http://localhost:5175') . env('EXAM_REDIRECT', '/teacher'),
    'url_forgot_pass' => env('URL_FE', 'http://localhost:5175') . env('FORGOT_PASS', '/reset-password'),
    'path' => [
        'question' => 'question',
        'avatar' => 'avatar',
    ],
];
