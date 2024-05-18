<?php

declare(strict_types=1);

namespace Core;

use ReflectionClass;

class ResolveContainer
{
	public function parameters($method, Container $container)
	{
		return array_map( // Map the parameters of the method
			fn ($param) => $container->get($param->getType()->getName()), // Get the type of the parameter and resolve it from the container
			$method->getParameters()
		);		
	}

	/**
	 * Get an instance of a class with its dependencies resolved
	 * 
	 * @param string $key The class to resolve
	 * 
	 * @return mixed
	 */
	public function instance(string $key, Container $container)
	{
		$reflaction = new ReflectionClass($key); //The reflection works as a mirror of the class

		$constructor = $reflaction->getConstructor(); // Get the constructor of the class
		if (!$constructor) { // If the class does not have a constructor return a new instance of the class
			return new $key;
		}

		return $reflaction->newInstanceArgs( // Return a new instance of the class with its dependencies resolved
			$this->parameters($constructor, $container)
		);
	}
}