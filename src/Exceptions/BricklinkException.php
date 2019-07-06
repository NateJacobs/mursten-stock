<?php

namespace NateJacobs\MurstenStock\Exceptions;

use Psr\Http\Message\ResponseInterface;

class BricklinkException extends \RuntimeException
{
	public function __construct($message, ResponseInterface $response)
	{
		$code = $response && !($response instanceof PromiseInterface)
            ? $response->getStatusCode()
            : 0;

		$this->response = $response;

		parent::__construct($message, $code, null);
	}

	public function getResponse()
	{
		return $this->response;
	}
}
