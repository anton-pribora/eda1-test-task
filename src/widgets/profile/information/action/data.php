<?php

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $record Project\Profile */

$record = $this->argument();

ReturnJson(['data' => $this->include('encodeData.php')]);
