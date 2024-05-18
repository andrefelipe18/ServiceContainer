<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
	private array $arrayUsers = [
		['id' => 1, 'name' => 'John Doe'],
		['id' => 2, 'name' => 'Jane Doe'],
		['id' => 3, 'name' => 'John Smith'],
		['id' => 4, 'name' => 'Jane Smith'],
	];

	public function find(int $id)
	{
		foreach ($this->arrayUsers as $user) {
			if ($user['id'] === $id) {
				return $user;
			}
		}
	}
}