<?php

namespace NateJacobs\MurstenStock;

use NateJacobs\MurstenStock\Exceptions\AuthException;

class Client
{
	public function setAuth($auth_params = null)
	{
		$keys = [
			'consumer_key',
			'consumer_secret',
			'token',
			'token_secret'
		];

		if(is_null($auth_params)) {
			throw new AuthException('Missing bricklink API authenication parameters.');
		}

		if(false === is_array($auth_params)) {
			throw new AuthException('The authenication parameters variable must be an array');
		}

		if(false === $this->array_keys_exists($keys, $auth_params)) {
			throw new AuthException('Missing bricklink API authenication parameters.');
		}

		$this->auth = $auth_params;
	}

	private function array_keys_exists(array $keys, array $arr) {
		return !array_diff_key(array_flip($keys), $arr);
	}
}
