<?php

// Контроль видимости разделов меню
$visible = function ($section) {
    return Identity()->getPermit($section) === true;
};

return [
    'menu' => [
        ['text' => 'Тестовое задание', 'url' => ShortUrl('@consultant/test-task/'), 'submenu' => [
            ['text' => 'Текст задания', 'url' => ShortUrl('@consultant/test-task/text.php')],
            ['text' => 'Решение', 'url' => ShortUrl('@consultant/test-task/solution.php')],
        ]],
        ['text' => 'Администрирование', 'visible' => $visible('administration'), 'url' => ShortUrl('@consultant/administration/'), 'submenu' => [
            ['text' => 'Пользователи', 'url' => ShortUrl('@consultant/administration/profiles/')],
            ['text' => 'Роли', 'url' => ShortUrl('@consultant/administration/roles/')],
        ]],
        ['text' => 'Инструменты', 'visible' => $visible('tools'), 'url' => ShortUrl('@consultant/tools/'), 'submenu' => [
            ['text' => 'Генератор разделов', 'visible' => $visible('tools'), 'url' => ShortUrl(__dir('development/tools/partgen/'))],
            ['text' => 'Генератор виджетов', 'visible' => $visible('tools'), 'url' => ShortUrl(__dir('development/tools/widgetgen/'))],
            ['text' => 'phpinfo()', 'visible' => $visible('tools'), 'url' => ShortUrl(__dir('development/tools/phpinfo.php'))],
            ['text' => 'Свободное место', 'visible' => $visible('tools'), 'url' => ShortUrl(__dir('development/tools/df.php'))],
        ]]
    ],

    'usermenu' => [
        ['text' => Icon('profile') . 'Мой профиль' , 'url' => Identity()->getActiveEntry()->urlAsset('url.view')],
        false,
        ['text' => '<i class="bi bi-box-arrow-right me-1"></i>Выйти', 'url' => ShortUrl('@root/consultant/logout/')],
    ],
];
