<?php

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

$autoloadFile = __DIR__.'/../vendor/autoload.php';

if (!file_exists($autoloadFile)) {
    die('The autoload file could not be located.');
}

require_once $autoloadFile;

ini_set('display_errors', 1);
error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/bootstrap.php';
require __DIR__.'/../src/controllers.php';

$app->run();
