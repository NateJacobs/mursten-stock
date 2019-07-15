<?php

namespace NateJacobs\MurstenStock\Responses;

use Carbon\Carbon;

class Feedback
{
	public function __construct($feedback)
	{
		$this->feedbackID = $this->setFeedbackID($feedback);
		$this->orderID = $this->setOrderID($feedback);
		$this->rating = $this->setRating($feedback);
		$this->fromUser = $this->setFromUser($feedback);
		$this->toUser = $this->setToUser($feedback);
		$this->ratingDate = $this->setRatingDate($feedback);
		$this->text = $this->setComment($feedback);

		return $this;
	}

	private function setRating($feedback)
	{
		switch ($feedback->rating) {
			case 0:
				$rating = [
					'code' => $feedback->rating,
					'description' => 'Praise',
					'type' => $this->setFeedbackType($feedback->rating_of_bs),
				];
				break;
			case 1:
				$rating = [
					'code' => $feedback->rating,
					'description' => 'Neutral',
					'type' => $this->setFeedbackType($feedback->rating_of_bs),
				];
				break;
			case 2:
				$rating = [
					'code' => $feedback->rating,
					'description' => 'Complaint',
					'type' => $this->setFeedbackType($feedback->rating_of_bs),
				];
				break;
			default:
				$rating = [
					'code' => '',
					'description' => '',
					'type' => '',
				];
				break;
		}

		return $rating;
	}

	private function setFeedbackType($type)
	{
		if ('S' === strtoupper($type)) {
			$expanded_type = 'Seller';
		} elseif ( 'B' === strtoupper($type)) {
			$expanded_type = 'Buyer';
		}

		return $expanded_type;
	}

	private function setFeedbackID($feedback)
	{
		return $feedback->feedback_id;
	}

	private function setOrderID($feedback)
	{
		return $feedback->order_id;
	}

	private function setFromUser($feedback)
	{
		return $feedback->from;
	}

	private function setToUser($feedback)
	{
		return $feedback->to;
	}

	private function setRatingDate($feedback)
	{
		$date = Carbon::parse($feedback->date_rated);

		return $date;
	}

	private function setComment($feedback)
	{
		$reply = isset($feedback->reply) ? $feedback->reply : '';
		return ['comment' => trim($feedback->comment), 'reply' => trim($reply)];
	}
}
