<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default source repository type
    |--------------------------------------------------------------------------
    |
    | The default source repository type you want to pull your updates from.
    |
    */

    'default' => env('UPDATER_SOURCE', 'github'),

    /*
    |--------------------------------------------------------------------------
    | Version installed
    |--------------------------------------------------------------------------
    |
    | Set this to the version of your software installed on your system.
    |
    */

    'version_installed' => env('UPDATER_VERSION_INSTALLED', ''),

    /*
    |--------------------------------------------------------------------------
    | Repository types
    |--------------------------------------------------------------------------
    |
    | A repository can be of different types, which can be specified here.
    | Current options:
    | - github
    | - http
    |
    */

    'repository_types' => [
        'github' => [
            'type' => 'github',
            'repository_vendor' => env('UPDATER_REPO_VENDOR', ''),
            'repository_name' => env('UPDATER_REPO_NAME', ''),
            'repository_url' => '',
            'download_path' => env('UPDATER_DOWNLOAD_PATH', '/tmp'),
            'private_access_token' => env('UPDATER_GITHUB_PRIVATE_ACCESS_TOKEN', ''),
            'use_branch' => env('UPDATER_USE_BRANCH', ''),
        ],
        'http' => [
            'type' => 'http',
            'repository_url' => env('UPDATER_REPO_URL', ''),
            'pkg_filename_format' => env('UPDATER_PKG_FILENAME_FORMAT', 'v_VERSION_'),
            'download_path' => env('UPDATER_DOWNLOAD_PATH', '/tmp'),
            'private_access_token' => env('UPDATER_HTTP_PRIVATE_ACCESS_TOKEN', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude folders from update
    |--------------------------------------------------------------------------
    |
    | Specific folders which should not be updated and will be skipped during the
    | update process.
    |
    | Here's already a list of good examples to skip. You may want to keep those.
    |
    */

    'exclude_folders' => [
        '__MACOSX',
        'node_modules',
        'bootstrap/cache',
        'bower',
        'storage/app',
        'storage/framework',
        'storage/logs',
        'storage/updater',
        'vendor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Logging
    |--------------------------------------------------------------------------
    |
    | Configure if fired events should be logged
    |
    */

    'log_events' => env('UPDATER_LOG_EVENTS', false),

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Specify for which events you want to get notifications. Out of the box you can use 'mail'.
    |
    */

    'notifications' => [
        'notifications' => [
            \Osen\Updater\Notifications\Notifications\UpdateSucceeded::class => ['mail'],
            \Osen\Updater\Notifications\Notifications\UpdateFailed::class => ['mail'],
            \Osen\Updater\Notifications\Notifications\UpdateAvailable::class => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Osen\Updater\Notifications\Notifiable::class,

        'mail' => [
            'to' => [
                'address' => env('UPDATER_MAILTO_ADDRESS', 'notifications@example.com'),
                'name' => env('UPDATER_MAILTO_NAME', ''),
            ],

            'from' => [
                'address' => env('UPDATER_MAIL_FROM_ADDRESS', 'updater@example.com'),
                'name' => env('UPDATER_MAIL_FROM_NAME', 'Update'),
            ],
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Register custom artisan commands
    |---------------------------------------------------------------------------
    */

    'artisan_commands' => [
        'pre_update' => [
            //'command:signature' => [
            //    'class' => Command class
            //    'params' => []
            //]
        ],
        'post_update' => [

        ],
    ],

];
