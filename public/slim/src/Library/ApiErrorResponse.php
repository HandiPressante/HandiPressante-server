<?php
namespace App\Library;

class ApiErrorResponse extends ApiResponse {
	public function __construct($errorText) {
		parent::__construct(false, $errorText, null);
	}
};
