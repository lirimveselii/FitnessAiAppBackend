<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'together' => [
        'api_key' => env('TOGETHER_API_KEY'),
        'base_url' => env('TOGETHER_BASE_URL', 'https://api.together.xyz/v1/chat/completions'),
        'model' => env('TOGETHER_MODEL', 'meta-llama/Llama-3.3-70B-Instruct-Turbo-Free'),
        'timeout' => env('TOGETHER_TIMEOUT', 200),
        'retries' => env('TOGETHER_RETRIES', 0),
        'retry_delay' => env('TOGETHER_RETRY_DELAY_MS', 100),
    ],

];
