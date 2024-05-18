<?php

declare(strict_types=1);

namespace App\Library;

use App\Library\NewsLetter;

class Auth
{
	public function __construct(
		private NewsLetter $newsLetter
	){}

	public function auth()
	{
		return $this->newsLetter->send();
	}
}