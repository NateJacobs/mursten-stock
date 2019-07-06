<?php

namespace NateJacobs\MurstenStock\Resources;

use NateJacobs\MurstenStock\Request;
use NateJacobs\MurstenStock\Response\Item as ItemResponse;
use NateJacobs\MurstenStock\Response\Category as CategoryResponse;
use NateJacobs\MurstenStock\Exceptions\MissingParamsException;

class Item extends Request
{
	public function getItem($id = null, $type = 'part')
	{
		if (is_null($id)) {
			return new MissingParamsException('You must pass an item ID.');
		}

		if (false !== in_array(strtoupper($type), ['MINIFIG, PART, SET, BOOK, GEAR, CATALOG, INSTRUCTION, UNSORTED_LOT, ORIGINAL_BOX'])) {
			return new MissingParamsException('You must pass a valid item type.');
		}

		try {
			$categories = $this->request('get', 'categories');
			return $this->createReturnObject($this->request('get', 'items'.'/'.$type.'/'.$id), $categories);
		} catch(\Exception $e) {
			return $e;
		}
	}

	private function createReturnObject($response, $categories)
	{
		$response = is_array($response) ? $response : [$response];
		$categories = is_array($categories) ? $categories : [$categories];

		foreach ($categories as $category) {
			$categoryArray[] = new CategoryResponse($category);
		}

		foreach ($response as $object) {
			$item[] = new ItemResponse($object, $categoryArray);
		}

		return $item;
	}
}
