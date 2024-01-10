<?php

Config()->setup([
    'console' => [
        'default_user' => '1000',
        '_SERVER'      => [
            'HTTPS'       => 'off',
            'SERVER_PORT' => '80',
            'HTTP_HOST'   => '127.0.0.1:4101',
        ],
    ],
]);
