<?php

use Controllers\DatabaseController;

require __DIR__ . '/vendor/autoload.php';

App::run();

DatabaseController::getConnection();