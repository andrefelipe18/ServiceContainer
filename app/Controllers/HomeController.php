<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Library\Auth;
use App\Library\NewsLetter;
use DI\Attribute\Inject;

class HomeController
{	
	public function __construct(
		private UserRepositoryInterface $userRepository,
		private Auth $auth
	){}
	
	#[Inject(['TestingKey' => 'KeyInjectInHomeController'])]
	public function index(
		UserRepositoryInterface $userRepository,
		NewsLetter $newsLetter,
		$TestingKey
	)
	{
		dd(
		$userRepository->find(1),
		 'NewsLetter Resolving in __construct params' . $this->auth->auth(), 
		 'NewsLetter Resolving in method params' . $newsLetter->send(),
		 $TestingKey
		);
	}
}