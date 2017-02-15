<?php

// Uncomment this line if you must temporarily take down your site for maintenance.

define('WWW_DIR', __DIR__);

$container = require __DIR__ . '/../app/bootstrap.php';

$container->getService('application')->run();
