<?php

return [
    'default' => [
        'name' => env('MENSA_DEFAULT_NAME', ''),
        'price' => env('MENSA_DEFAULT_PRICE', 3.50),
        'max_signups' => env('MENSA_DEFAULT_MAX_SIGNUPS', 42),
        'start_time' => env('MENSA_DEFAULT_START_TIME', '18:30'),
        'closing_time' => env('MENSA_DEFAULT_CLOSING_TIME', '16:00'),
    ],

    'contact' => [
        'bar' => env('MENSA_BAR_PHONE', ''),
        'mail' => env('MENSA_CONTACT_MAIL', ''),
        'printer' => env('MENSA_PRINTER_MAIL', ''),
    ],

    'minimum' => [
        'paying_signins' => env('MENSA_MINIMUM_PAYING_SIGNINS', 6),
        'second_cook' => env('MENSA_SECOND_COOK', 15),
        'second_dishwasher' => env('MENSA_SECOND_DISHWASHER', 15),
    ],

    'price_reduction' => [
        'kitchen' => env('MENSA_SUBTRACT_KITCHEN', 0.3),
        'dishwasher' => env('MENSA_SUBTRACT_DISHWASHER', 0.5),
    ],

    'consumptions' => [
        'cook' => [
            'base' => env('MENSA_CONSUMPTIONS_COOK_BASE', 2),
            '1_per_x_guests' => env('MENSA_CONSUMPTIONS_COOK_1_PER_X_GUESTS', 15),
            'max' => env('MENSA_CONSUMPTIONS_COOK_MAX', 4),
        ],
        'dishwasher' => [
            'base' => env('MENSA_CONSUMPTIONS_DISHWASHER_BASE', 2),
            'split_1_per_x_guests' => env('MENSA_CONSUMPTIONS_DISHWASHER_SPLIT_1_PER_X_GUESTS', 6),
            'max' => env('MENSA_CONSUMPTIONS_DISHWASHER_MAX', 6),
        ],
        'price' => env('MENSA_CONSUMPTIONS_PRICE'),
    ],

    'remote_user' => [
        'admin_group_id' => env('REMOTE_USER_ADMIN_GROUP_ID'),
        'update_time' => env('REMOTE_USER_UPDATE_TIME'),
        'email_suffix' => env('remote_user_email_suffix'),
    ],

    'url' => [
        'forgot_password' => env('MENSA_URL_FORGOT_PASSWORD'),
    ],

    'service_users' => [
        'users' => [
            [
                'name' => 'Bar soos',
                'token' => env('MENSA_ACCOUNTURL_BAR001'),
            ],
            [
                'name' => 'Bar filmzaal',
                'token' => env('MENSA_ACCOUNTURL_BAR002'),
            ],
        ],
        'whitelisted_ips' => explode(',', env('MENSA_ACCOUNT_WHITELIST_IPS')),
    ],

];
