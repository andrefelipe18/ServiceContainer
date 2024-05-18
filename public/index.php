<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;

require __DIR__ . '/bootstrap.php';

/** @var DI\Container $container */
$router = new Router($container);
$router->create(require '../app/Routes/web.php');