<?php

namespace Core;

class Router
{
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
			$controller = new $this->controller();
			if(method_exists($controller, $this->method)) {
				return $controller->{$this->method}();
			}
		}
	}
}