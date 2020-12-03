<?php

define('__ROOT__', dirname(__FILE__) . '/');

require __DIR__ . '/vendor/autoload.php';

$frontController = new FrontController();

$frontController->run();