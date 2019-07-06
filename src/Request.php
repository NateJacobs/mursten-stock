<?php

namespace NateJacobs\MurstenStock;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Exception\RequestException;
use NateJacobs\MurstenStock\Client as MurstenStock;
use NateJacobs\MurstenStock\Exceptions\ResponseException;
use NateJacobs\MurstenStock\Exceptions\BricklinkException;

class Request
{
    protected $base_url = 'https://api.bricklink.com/api/store/v1/';

    public function __construct(MurstenStock $mursten_stock)
    {
		$this->auth = $mursten_stock->auth;
    }

    protected function buildAPIClient()
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $this->auth['consumer_key'],
            'consumer_secret' => $this->auth['consumer_secret'],
            'token'           => $this->auth['token'],
            'token_secret'    => $this->auth['token_secret']
        ]);

        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => $this->base_url,
            'handler' => $stack,
            'auth' => 'oauth',
			'http_errors' => true,
        ]);
    }

	private function send($method, $path, $params)
	{
		return $this->client->{$method}(
			$path,
			['query' => $params]
		);
	}

	protected function request($method, $path, $params = [])
	{
		$this->buildAPIClient();

		try {
			$response = $this->send($method, $path, $params);

			try {
				return $this->check_bricklink_status($response);
			} catch(\Exception $e) {
				throw new ResponseException($e);
			}
		} catch (RequestException $e) {
			throw new ResponseException($e);
		}
	}

	private function check_bricklink_status($response)
	{
		$response_json = json_decode($response->getBody()->getContents());

		if ('no error' === strtolower(json_last_error_msg())) {
			if (in_array($response_json->meta->code, [200, 201, 204]) ) {
				return $response_json->data;
			} else {
				$message = $response_json->meta->message.'. '.$response_json->meta->description;
				throw new BricklinkException($message, $response);
			}
		} else {
			throw new BricklinkException('The response could not be parsed as a JSON string.', $response);
		}
	}
}
