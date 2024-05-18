<?php

return [
	'Core\Router' => fn($container) => new Core\Router($container),
	'App\Controllers\HomeController' => fn($container) => new App\Controllers\HomeController(
		$container->get('App\Interfaces\UserRepositoryInterface'),
		$container->get('App\Library\Auth')
	),
	'App\Interfaces\UserRepositoryInterface' => fn() => new App\Repositories\UserRepository(),
	'App\Library\Auth' => fn() => new App\Library\Auth(new App\Library\NewsLetter()),
	'App\Library\NewsLetter' => fn() => new App\Library\NewsLetter(),
	'Core\ResolveContainer' => fn() => new Core\ResolveContainer(),
	'Core\Container' => fn($container) => new Core\Container($container->get('Core\ResolveContainer')),
	'Core\Application' => fn($container) => new Core\Application($container)
];