<?php

namespace NateJacobs\MurstenStock\Response;

class Item
{
	public function __construct($item, $categories)
	{
		$this->itemNumbers = $this->setNumbers($item);
		$this->category = $this->setCategory($item, $categories);
		$this->name = $item->name;
		$this->type = $this->setType($item);
		$this->image = $this->setImageURLs($item);
		$this->yearReleased = $item->year_released;
		$this->description = isset($item->description) ? $item->description : '';
		$this->isObsolete = $item->is_obsolete;

		return $this;
	}

	private function setNumbers($item)
	{
		$numbers = [
			'bricklinkID' => $item->no,
			'alternateID' => isset($item->alternate_no) ? $item->alternate_no : '',
		];

		return $numbers;
	}

	private function setCategory($item, $categories)
	{
		$categoryKey = array_search($item->category_id, array_column($categories, 'categoryID'));

		return $categories[$categoryKey];
	}

	private function setType($item)
	{
		$type_array = [
			'MINIFIG',
			'PART',
			'SET',
			'BOOK',
			'GEAR',
			'CATALOG',
			'INSTRUCTION',
			'UNSORTED_LOT',
			'ORIGINAL_BOX',
		];

		$type = in_array($item->type, $type_array) ? ucwords(strtolower($item->type)) : 'Not Defined';

		return $type;
	}

	private function setImageURLs($item)
	{
		$image = [
			'url' => $item->image_url,
			'thumbnail_url' => $item->thumbnail_url,
		];

		return $image;
	}

	private function setDimensions($item)
	{
		$dimensions = [
			'weight' => $item->weight,
			'length' => $item->dim_x,
			'width' => $item->dim_y,
			'height' => $item->dim_z,
		];

		return $dimensions;
	}
}
