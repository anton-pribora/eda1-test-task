<?php

Layout()->setVar('title', 'Решение');
Layout()->append('breadcrumbs', new \ApCode\Html\Element\A('Решение', ShortUrl(__FILE__)));

$params = [
    'ingredients' => Request()->get('ingredients', 'dcciii'),
    'calcType'    => Request()->get('calcType')
];

if (Request()->isPost() && Request()->get('action') === 'calculate') {
    [$result, $error, $stats] = Module('project')->execute('ingredients/calculate.php', $params);

    if ($error) {
        ReturnJsonError($error, 'invalid_calculating', ['stats' => $stats]);
    }

    ReturnJson([
        'calculation' => $result,
        'stats' => $stats,
    ]);
}

Template()->render(__dir('.solution/view_form.php'), $params);
