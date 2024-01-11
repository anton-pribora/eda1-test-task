<?php

/* @var $this \ApCode\Executor\PhpFileExecutor */

// Входящий набор ингредиентов
$input = $this->param('ingredients');

// Тип калькулятора
$calcType = $this->param('calcType');

if (preg_match('/\W/', $calcType)) {
    return [null, "Указаны неверные символы в типе калькулятора ({$calcType})"];
}

$file = __dir(".calculate/{$calcType}.php");

if (!file_exists($file)) {
    return [null, "Указан неверный тип калькулятора ({$calcType})"];
}

return $this->include($file);
