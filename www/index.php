<?php

define('WWW_DIR', __DIR__);

$container = require __DIR__ . '/../app/bootstrap.php';

$container->getService('application')->run();
