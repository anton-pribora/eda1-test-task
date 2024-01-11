<?php

/* @var $this \ApCode\Executor\PhpFileExecutor */

// Входящий набор ингредиентов
use Project\Ingredient;

$input = $this->param('ingredients');

$result = [];

// Составляем карту соответствий кода ингредиента его типу
$ingredientTypeMapByCode = [];

foreach (\Project\IngredientTypeRepository::findMany([]) as $type) {
    $ingredientTypeMapByCode[$type->code()] = $type;
}

// Формируем список ингредиентов для последующей комбинации
$ingredients = [];

foreach (mb_str_split($input) as $code) {
    // Проверяем, есть ли запрашиваемый ингредиент в нашей карте
    if (empty($ingredientTypeMapByCode[$code])) {
        // Упс, ингредиент не найден, ругаемся
        return [null, "Не найден ингредиент с кодом '{$code}'"];
    }

    // Добавляем продукты в комбинаторику
    $ingredients[] = $ingredientTypeMapByCode[$code]->ingredients();
}

// Комбинируем продукты
foreach (new CombineIterator(...$ingredients) as $products) {
    /* @var $products Ingredient[] */

    // Поскольку мы не должны добавлять дублирующиеся продукты, нужно составить уникальный код
    $ids = array_map(static fn(Ingredient $i) => $i->id(), $products);

    // Уникальные ид ингредиентов
    $uniqIds = array_unique($ids);

    // Запрещаем дубли
    if (count($ids) !== count($uniqIds)) {
        continue;
    }

    // Уникальный ключ строки результата, не зависящий от положения ингредиентов
    sort($uniqIds);
    $key = join(',', $uniqIds);

    if (isset($result[$key])) {
        continue;
    }

    $price = 0;
    $resultProducts = [];

    foreach ($products as $product) {
        $price += $product->price();

        $resultProducts[] = [
            'type' => $product->type()->title(),
            'value' => $product->title(),
        ];
    }

    $result[$key] = [
        'products' => $resultProducts,
        'price' => $price,
    ];
}


return [array_values($result), null];
