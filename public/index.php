<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$routes = require '../app/Routes/web.php';

$router = new Router();
$router->create($routes);