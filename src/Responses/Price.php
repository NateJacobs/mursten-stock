<?php

namespace NateJacobs\MurstenStock\Responses;

class Price
{
	public function __construct($price)
	{
		$this->aggregatePrices = $this->setPrices($price);
		$this->currencyCode = $price->currency_code;
		$this->condition = $this->setCondition($price);
		$this->unitQuantity = $price->unit_quantity;
		$this->totalQuantity = $price->total_quantity;
		$this->priceDetail = $price->price_detail;

		return $this;
	}

	private function setPrices($price)
	{
		$formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

		$price_array = [
			'averagePrice' => $formatter->formatCurrency(
				$price->avg_price,
				$price->currency_code
			),
			'maximumPrice' => $formatter->formatCurrency(
				$price->max_price,
				$price->currency_code
			),
			'minimumPrice' => $formatter->formatCurrency(
				$price->min_price,
				$price->currency_code
			),
			'quantityAveragePrice' => $formatter->formatCurrency(
				$price->qty_avg_price,
				$price->currency_code
			),
		];

		return $price_array;
	}

	private function setCondition($price)
	{
		switch ($price->new_or_used) {
			case 'N':
				$condition = 'New';
				break;
			case 'U':
				$condition = 'Used';
				break;
			default:
				$condition = '';
				break;
		}

		return $condition;
	}
}
