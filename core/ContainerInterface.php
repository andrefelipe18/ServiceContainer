<?php

declare(strict_types=1);

namespace Core;

interface ContainerInterface
{
	public function bind(string $key, mixed $value);

	public function addDefinitions(array|string $definitions);

	public function get(string $key): mixed;
}