<?php


use Sdh\Veselice\Model\StaticFiles;

$container = require __DIR__ . '/../app/bootstrap.php';
$output_dir = __DIR__ . "/../www";

$css = new \MatthiasMullie\Minify\CSS();
$css->add(__DIR__ . "/../www/new_style/bootstrap.min.css");
$css->add(__DIR__ . "/../www/new_style/my.css");

$css->minify($output_dir . "/styles_" . StaticFiles::CSS_VERSION . ".css");
echo "\nCSS has been generated\n";

$js = new \MatthiasMullie\Minify\JS();
$js->add(__DIR__ . "/../www/js/jquery-3.1.1.min.js");
$js->add(__DIR__ . "/../www/js/bootstrap.min.js");

$js->minify($output_dir . "/scripts_" . StaticFiles::JS_VERSION . ".js");
echo "JS has been generated\n\n";