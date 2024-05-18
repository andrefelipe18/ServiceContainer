<?php

namespace Core;

use ReflectionMethod;
use DI\Container;

class Router
{
	public function __construct(
		private Container $container
	){}

	private string $controller;
	private string $method;
	public function create(array $routes)
	{
		foreach ($routes as $uri => $route) {
			if($uri === $_SERVER['REQUEST_URI']) { //If the URI matches the current request URI
				$this->controller = $route[0];
				$this->method = $route[1];
			}
		}
		$this->makeInstance();
	}

	private function makeInstance()
	{
		if(class_exists($this->controller)) {
			$controller = $this->container->get($this->controller); //Get the controller from the container

			$method = new ReflectionMethod($controller, $this->method); //Get the method from the controller

			if(method_exists($controller, $this->method)) {
				return $controller->{$this->method}();
			}
		}
	}
}