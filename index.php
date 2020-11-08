<?php

define('__ROOT__', '/Users/macbook/PhpstormProjects/MultiplexTrack/');

require __DIR__ . '/vendor/autoload.php';

$frontController = new FrontController();

$frontController->run();