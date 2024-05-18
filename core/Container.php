<?php

declare(strict_types=1);

namespace Core;

use Closure;

class Container implements ContainerInterface
{
	/**
	 * The bindings in the container
	 * 
	 * @var array<string, mixed>
	 */
	private array $bindings = [];

	public function __construct(
		public readonly ResolveContainer $resolveContainer
	) {}

	/**
	 * Bind a key to a value (Set value in the container)
	 * 
	 * @param string $key The key to bind
	 * @param mixed $value The value to return when the key is resolved
	 * 
	 * @return void
	 */
	public function bind(
		string $key, //The key to bind
		mixed $value //The value to return when the key is resolved
	) {
		$this->bindings[$key] = $value;
	}

	/**
	 * Resolve a key from the container
	 * 
	 * @param string $key The key to resolve
	 * 
	 * @return mixed
	 */
	public function get(
		string $key
	): mixed {
		if (isset($this->bindings[$key])) {
			$bind = $this->bindings[$key];
			if ($bind instanceof Closure) {
				return $bind();
			}

			return $bind;
		}

		if (class_exists($key)) {
			return $this->resolveContainer->instance($key, $this);
		}

		return null;
	}

	/**
	 * Add definitions to the container
	 * 
	 * @param string|array<string, mixed> $definitions The definitions to add to the container
	 * 
	 * @return void
	 */
	public function addDefinitions(
		string|array $definitions
	) {
		if (!is_array($definitions) && !is_string($definitions)) {
			throw new \InvalidArgumentException('Definitions must be an array or a string');
		}

		if (is_string($definitions) && ($file = APP_PATH . DIRECTORY_SEPARATOR . $definitions) && file_exists($file)) {
			$definitions = require $definitions;
		}

		foreach ($definitions as $key => $dependency) {
			$this->bind($key, $dependency);
		}
	}
}