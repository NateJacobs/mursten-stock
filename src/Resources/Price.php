<?php

namespace NateJacobs\MurstenStock\Resources;

use NateJacobs\MurstenStock\Request;
use NateJacobs\MurstenStock\Responses\Price as PriceResponse;
use NateJacobs\MurstenStock\Exceptions\MissingParamsException;

class Price extends Request
{
	public function getPrice($id, $type = 'set', $options = [])
	{
		$defaults = [
			'guide_type' => 'sold',
			'new_or_used' => 'N',
		];

		if (false === is_array($options)) {
			return new MissingParamsException('The options provided must be an array.');
		}

		if (false !== in_array(strtoupper($type), ['MINIFIG, PART, SET, BOOK, GEAR, CATALOG, INSTRUCTION, UNSORTED_LOT, ORIGINAL_BOX'])) {
			return new MissingParamsException('You must pass a valid item type.');
		}

		if (is_array($options)) {
			$options = array_merge( $defaults, $options );
		}

		try {
			return $this->createReturnObject($this->request('get', 'items'.'/'.$type.'/'.$id.'/price', $options));
		} catch(\Exception $e) {
			return $e;
		}
	}
	private function createReturnObject($response)
	{
		$response = is_array($response) ? $response : [$response];

		foreach ($response as $object) {
			$price[] = new PriceResponse($object);
		}

		return $price;
	}
}
