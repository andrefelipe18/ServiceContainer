<?php

declare(strict_types=1);

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

$container = new DI\ContainerBuilder();

$container->useAttributes(true);

$container->addDefinitions([
	UserRepositoryInterface::class => fn() => new UserRepository(),
	'KeyInjectInHomeController' => 'ValueInjectInHomeController'
]);

$container = $container->build();

return $container;