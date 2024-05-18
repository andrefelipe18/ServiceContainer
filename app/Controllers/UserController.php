<?php

declare(strict_types=1);

namespace App\Controllers;

class UserController
{
	public function index()
	{
		ds('Hello, LaraDumps!');
		return 'Hello, LaraDumps!';
	}
}