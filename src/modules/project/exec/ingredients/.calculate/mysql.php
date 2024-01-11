<?php

/* @var $this \ApCode\Executor\PhpFileExecutor */

// Входящий набор ингредиентов
$input = $this->param('ingredients');

// Подключаемся к базе данных
$db = Db();

// Составляем карту соответствий кода ингредиента его типу
$ingredientTypeMap = [];

foreach (\Project\IngredientTypeRepository::findMany([]) as $type) {
    $ingredientTypeMap[$type->code()] = [$type->id(), $type->title()];
}

// Формируем список типов ингредиентов
$ingredientTypes = [];

foreach (mb_str_split($input) as $code) {
    // Проверяем, есть ли запрашиваемый ингредиент в нашей карте
    if (empty($ingredientTypeMap[$code])) {
        // Упс, ингредиент не найден, ругаемся
        return [null, "Не найден ингредиент с кодом '{$code}'"];
    }

    // Добавляем идентификатор типа ингредиента в список
    $ingredientTypes[] = $ingredientTypeMap[$code];
}

$result = [];

// Если список ингредиентов был таки передан, нужно скомбинировать их
if ($ingredientTypes) {
    // Комбинируем через декартово произведение множеств
    // Для этого используем джойны мускуля
    $columns = [];
    $tables  = [];
    $where   = [];
    $order   = [];

    foreach ($ingredientTypes as $i => [$id]) {
        $columns[] = "t{$i}.id t{$i}_id, t{$i}.title t{$i}_title, t{$i}.price t{$i}_price";
        $tables[]  = "ingredient t{$i}";
        $where[]   = "t{$i}.type_id = {$id}";
        $order[]   = "t{$i}.id";
    }

    // Запрещаем дублирование ингредиентов, если их несколько
    if (count($ingredientTypes) > 1) {
        $keys = array_keys($ingredientTypes);

        foreach ($ingredientTypes as $i => [$id]) {
            $otherIndices = array_filter($keys, static fn($k) => $k != $i);
            $otherColumns = array_map(static fn ($i) => "t{$i}.id", $otherIndices);
            $where[] = "t{$i}.id NOT IN (" . join(', ', $otherColumns) . ')';
        }
    }

    // Список столбцов по ингредиентам
    $columns = join(', ', $columns);

    // Список таблиц
    $firstTable = array_shift($tables);
    $tables = join(', ', $tables);
    $tables = $firstTable . ($tables ? ' JOIN ' . $tables : '');

    // Условия выборки
    $where = join(' AND ', $where);

    // Сортировка
    $order = join(', ', $order);

    // Выбираем данные
    $sql = "SELECT {$columns} FROM {$tables} WHERE {$where} ORDER BY {$order}";
    $res = $db->query($sql);

    // Список использованных ключей
    $usedKeys = [];

    // Формируем результирующий список
    foreach ($res->fetchAllRows() as $row) {
        // Список продуктов, стоимость, уникальный ключ
        $products = [];
        $price    = 0;
        $key      = [];

        foreach ($ingredientTypes as $i => [$typeId, $typeTitle]) {
            $price += floatval($row["t{$i}_price"]);
            $products[] = [
                'type'  => $typeTitle,
                'value' => $row["t{$i}_title"],
            ];

            $key[] = $row["t{$i}_id"];
        }

        // Сортируем ключ, чтобы не зависеть от положения ингредиент во входящем наборе
        sort($key);
        $key = join(',', $key);

        // Если продукт ещё не был добавлен в результат, добавляем
        if (!isset($usedKeys[$key])) {
            $result[] = [
                'products' => $products,
                'price'    => $price,
            ];

            $usedKeys[$key] = true;
        }
    }
}

return [$result, null];
