<?php

namespace NateJacobs\MurstenStock\Resources;

use NateJacobs\MurstenStock\Request;
use NateJacobs\MurstenStock\Exceptions\MissingParamsException;
use NateJacobs\MurstenStock\Response\Feedback as FeedbackResponse;

class Feedback extends Request
{
    public function getFeedback($direction = 'in')
    {
		try {
			return $this->createReturnObject($this->request('get', 'feedback', ['direction' => $direction]));
		} catch(\Exception $e) {
			return $e;
		}
    }

    public function getFeedbackFor($id = null)
    {
		if (is_null($id)) {
			return new MissingParamsException('You must pass a Feedback ID.');
		}

		if (false === is_int($id)) {
			return new MissingParamsException('The Feedback ID must be an integer.');
		}

		try {
			return $this->createReturnObject($this->request('get', 'feedback/'.$id));
		} catch(\Exception $e) {
			return $e;
		}
    }

	private function createReturnObject($response)
	{
		$response = is_array($response) ? $response : [$response];

		foreach ($response as $object) {
			$feedback[] = new FeedbackResponse($object);
		}

		return $feedback;
	}
}
