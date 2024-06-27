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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],



    'azure' => [
         'client_id' => env('AZURE_CLIENT_ID'),
         'client_secret' => env('AZURE_CLIENT_SECRET'),
         'redirect' => env('AZURE_REDIRECT_URI'),
         'tenant'  => env('AZURE_TENANT_ID'),
         'proxy' => env('PROXY'),
         'tenant_domain'  => env('AZURE_TENANT_DOMAIN'),
         'serviceprincipal_id'  => env('AZURE_SERVICEPRINCIPAL_ID'),
         'extension_app_id'  => env('AZURE_EXTENSION_APP_ID'),
         'search_filter'  =>[
             'group'  => [
                 'commissies' => env('AZURE_SEARCH_FILTER_GROUP_COMMISSIES'),
                 'leden' => env('AZURE_SEARCH_FILTER_GROUP_LEDEN')
             ]
         ],
         'role' => [
             'admin'  => env('AZURE_ROLE_ADMIN_VALUE'),
             'user'  => env('AZURE_ROLE_USER_VALUE')
         ]
    ]
];
