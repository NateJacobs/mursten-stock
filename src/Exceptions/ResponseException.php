<?php
namespace NateJacobs\MurstenStock\Exceptions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

/**
 * ResponseException is for response/request specific errors
 */
class ResponseException extends \Exception
{
	public function __construct($e)
	{
		$message = $e->getMessage();
        if ($e instanceof ClientException) {
            $response           = $e->getResponse();
            $responseBody       = $response->getBody()->getContents();
            $this->errorDetails = $responseBody;
            $message .= ' [details] ' . $this->errorDetails;
        } elseif ($e instanceof ServerException) {
            $message .= ' [details] Bricklink may be experiencing internal issues or undergoing scheduled maintenance.';
        } elseif ($e instanceof BricklinkException) {
			$response           = $e->getResponse();
			$responseBody       = $response->getBody()->getContents();
            $this->errorDetails = $responseBody;
		} elseif (! $e->hasResponse()) {
            $request = $e->getRequest();
            $message .= ' [url] ' . $request->getUri();
            $message .= ' [http method] ' . $request->getMethod();
            $message .= ' [body] ' . $request->getBody()->getContents();
        }

        parent::__construct($message, $e->getCode(), $e);
	}
}
