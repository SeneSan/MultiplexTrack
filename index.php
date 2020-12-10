<?php

define('__ROOT__', dirname(__FILE__) . '/');
define('OOPS_MESSAGE', 'Oops! Something went wrong.');

require __DIR__ . '/vendor/autoload.php';

$frontController = new FrontController();

$frontController->run();