<?php

/* @permission.name Примеры страниц */

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $consultant \Project\Profile */

$consultant = $this->argument(0);

return $consultant->permissionValueByFile(__FILE__) === true;
