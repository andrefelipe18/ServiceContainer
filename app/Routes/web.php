<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;

return [
	'/' => [HomeController::class, 'index'],
	'/user' => [UserController::class, 'index']
];