<?php

declare(strict_types=1);

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Core\Application;
use Core\Container;
use Core\ResolveContainer;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Definitions/constants.php';

$container = new Container(new ResolveContainer);

// $container->addDefinitions('definitions/container.php');

//or

//I refact this to multBind method
$container->addDefinitions([
	UserRepositoryInterface::class => fn () => new UserRepository(),
	//Or
	'Key' => 'Value'
]);

$container->bind(UserRepositoryInterface::class, fn () => new UserRepository());

Application::resolve($container);
