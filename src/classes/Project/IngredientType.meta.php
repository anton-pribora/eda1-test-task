<?php
// 'db.table'   - Обязательное поле, название таблицы
// 'db.idfield' - Обязательное поле, первичный ключ таблицы
// 'db.map'     - Карта сопоставления свойств объекта полям таблицы
//   'field'    - Обязательное поле, соответствует столбцу в базе данных
//   'title'    - Необязательное поле, содержит "человеческое" название поля
//   'encode'   - Необязательное поле, функция для кодирования значения поля при сохранении в базу
//   'decode'   - Необязательное поле, функция для декодирования значения поля при загрузке из базы
//   'default'  - Значение по-умолчанию, если установлена функция, то будет использован результат её работы
return [
    'db.table'   => 'ingredient_type',
    'db.idfield' => 'id',
    'db.map'     => [
        'id'    => [
            'field'   => 'id',
            'title'   => 'id',
            'encode'  => NULL,
            'decode'  => NULL,
            'default' => NULL,
        ],
        'title' => [
            'field'   => 'title',
            'title'   => 'title',
            'encode'  => NULL,
            'decode'  => NULL,
            'default' => NULL,
        ],
        'code'  => [
            'field'   => 'code',
            'title'   => 'code',
            'encode'  => NULL,
            'decode'  => NULL,
            'default' => NULL,
        ],
    ],
];
