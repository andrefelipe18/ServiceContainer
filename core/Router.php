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

			if(method_exists($controller, $this->method)) {
				return $this->container->call([$controller, $this->method]); //Call the method from the controller
			}
		}
	}
}