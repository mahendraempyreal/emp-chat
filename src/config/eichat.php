<?php

return [
    /*
    |-------------------------------------
    | Messenger display name
    |-------------------------------------
    */
    'name' => env('EICHAT_NAME', 'EI Chat Messenger'),

    /*
    |-------------------------------------
    | The disk on which to store added
    | files and derived images by default.
    |-------------------------------------
    */
    'storage_disk_name' => env('EICHAT_STORAGE_DISK', 'public'),

    /*
    |-------------------------------------
    | socket url
    |-------------------------------------
    */
    'socket_url' => env('EICHAT_SOCKET_URL', 'http://localhost:3003'),

    /*
    |-------------------------------------
    | Routes configurations
    |-------------------------------------
    */
    'routes' => [
        'as' => 'emp-chat.',
        'prefix' => env('EICHAT_ROUTES_PREFIX', 'empyreal-chat'), // emp-chat
        'middleware' => env('EICHAT_ROUTES_MIDDLEWARE', ['web','auth']),
        'namespace' => env('EICHAT_ROUTES_NAMESPACE', 'Mahendraempyreal\EmpChat\Controllers'),
    ],
    'api_routes' => [
        'prefix' => env('EICHAT_API_ROUTES_PREFIX', 'emp-chat/api'),
        'middleware' => env('EICHAT_API_ROUTES_MIDDLEWARE', ['api','auth:sanctum']),
        'namespace' => env('EICHAT_API_ROUTES_NAMESPACE', 'Mahendraempyreal\EmpChat\Controllers\Api'),
    ],

    /*
    |-------------------------------------
    | User Avatar
    |-------------------------------------
    */
    'user_avatar' => [
        'folder' => 'users-avatar',
        'default' => 'avatar.png',
    ],

    /*
    |-------------------------------------
    | Gravatar
    |
    | imageset property options:
    | [ 404 | mp | identicon (default) | monsterid | wavatar ]
    |-------------------------------------
    */
    'gravatar' => [
        'enabled' => true,
        'image_size' => 200,
        'imageset' => 'identicon'
    ],

    /*
    |-------------------------------------
    | Attachments
    |-------------------------------------
    */
    'attachments' => [
        'folder' => 'attachments',
        'download_route_name' => 'attachments.download',
        'allowed_images' => (array) ['png','jpg','jpeg','gif'],
        'allowed_files' => (array) ['zip','rar','txt'],
        'max_upload_size' => env('EICHAT_MAX_FILE_SIZE', 150), // MB
    ],

    /*
    |-------------------------------------
    | Messenger's colors
    |-------------------------------------
    */
    'colors' => (array) [
        '#2180f3',
        '#2196F3',
        '#00BCD4',
        '#3F51B5',
        '#673AB7',
        '#4CAF50',
        '#FFC107',
        '#FF9800',
        '#ff2522',
        '#9C27B0',
    ],
    /*
    |-------------------------------------
    | Sounds
    | You can enable/disable the sounds and
    | change sound's name/path placed at
    | `public/` directory of your app.
    |
    |-------------------------------------
    */
    'sounds' => [
        'enabled' => true,
        'public_path' => 'sounds/chatify',
        'new_message' => 'new-message-sound.mp3',
    ]
];
