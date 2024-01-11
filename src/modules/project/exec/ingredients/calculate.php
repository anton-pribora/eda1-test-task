<?php

/* @var $this \ApCode\Executor\PhpFileExecutor */

// Входящий набор ингредиентов
$input = $this->param('ingredients');

// Тип калькулятора
$calcType = $this->param('calcType');

if (preg_match('/\W/', $calcType)) {
    return [null, "Указаны неверные символы в типе калькулятора ({$calcType})", null];
}

$file = __dir(".calculate/{$calcType}.php");

if (!file_exists($file)) {
    return [null, "Указан неверный тип калькулятора ({$calcType})", null];
}

// Статистика
$systemQueries = Db()->totalQueries();
$systemQueriesTime = Db()->totalTime();

Timer('calc')->start();
[$result, $error] = $this->include($file);
$elapsed = Timer('calc')->stop()->elapsed();

$stats = [
    sprintf('Выполнено запросов: %s', Db()->totalQueries() - $systemQueries),
    sprintf('Время выполнения запросов: %.3f сек.', Db()->totalTime() - $systemQueriesTime),
    sprintf('Общее время работы: %.3f сек.', $elapsed),
    sprintf('Потребление памяти: %s', formatBytes(memory_get_peak_usage())),
];

return [$result, $error, $stats];
