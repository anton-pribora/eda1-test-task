<?php

Config()->setup([
    'project' => [
        'name' => 'Тестовое задание',
        'logo' => '<i class="bi bi-tools" style="font-size: 1.3rem;"></i>',
        'help' => 'help@example.com',
    ],

    'server' => [
        'webroot'    => '@root/web_docroot',
        'webactions' => '@root/web_actions',
    ],

    'db' => [
        'dsn'      => 'mysql:dbname=test_task;host=db;charset=utf8mb4',
        'login'    => 'test',
        'password' => 'test',
    ],

    'template' => [
        'name'   => 'public-bs5',  // default template name
        'layout' => [
            'main'  => 'main',
            'error' => 'main',
        ],
        'login' => [
            'template' => 'admin-bs5',
            'layout'   => 'login',
        ],
    ],

    'console' => [
        'default_user' => 'www-data',
        '_SERVER'      => [
            'HTTPS'       => 'on',
            'SERVER_PORT' => '443',
            'SERVER_NAME' => 'eda1-test-task',
            'HTTP_HOST'   => 'eda1-test-task',
            'REQUEST_URI' => '/',
        ],
    ],

    'mail' => [
        'defaultFrom' => 'default@email',
        'transports'  => [
            [
                'type' => 'file',
                'dir'  => ROOT_DIR . '/../mails/'. date('Y-m/d/'),
            ],
        ],
    ],

    'js' => [
        'ENV' => Config()->get('APPLICATION_ENV') === 'development' ? 'dev' : 'prod',  // Vue 3 extension (used by module misc vue3)
    ],
]);
