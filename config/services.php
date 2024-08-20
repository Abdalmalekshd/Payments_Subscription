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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model'=>App\Models\User::class,
        'pk' => 'pk_test_51PP0C5GWolDHikV5XteL1dI3cCE66Sh26TfhgzcZdsR2V7S8vv87Uv07MX7oL6Ug3qhJHXpiSapg6Df7UNgjRWt2009rfwXAJj',
        'sk' => 'sk_test_51PP0C5GWolDHikV5EFd8lt1EBIBQZxKxZPl9XDbKogV3TR1XR7Fdk5hEkg8Nq3VUPTHfHCpqmpD4bqhHY4qAIrMb00JpOiXS9Q',
    ],

];
