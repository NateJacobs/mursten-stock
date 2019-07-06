<?php

namespace NateJacobs\MurstenStock\Response;

class Category
{
	public function __construct($category)
	{
		$this->categoryID = $category->category_id;
		$this->categoryName = $category->category_name;
		$this->parentCategory = $category->parent_id;

		return $this;
	}
}
