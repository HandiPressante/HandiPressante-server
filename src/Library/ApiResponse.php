<?php
namespace App\Library;

class ApiResponse {
	private $success;
	private $errorText;
	private $data;

	public function __construct($success, $errorText, $data) {
		$this->success = $success;
		$this->errorText = $errorText;
		$this->data = $data;
	}

	public function toArray()
	{
		return [
			'success' => $this->success,
			'errorText' => $this->errorText,
			'data' => $this->data
		];
	}
};
