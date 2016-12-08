<?php
namespace App\Library;

class ApiSuccessResponse extends ApiResponse {
	public function __construct($data) {
		parent::__construct(true, '', $data);
	}
};
