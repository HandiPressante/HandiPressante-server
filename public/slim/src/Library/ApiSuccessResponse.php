<?php
namespace App\Library;

class ApiSuccessResponse extends ApiResponse {
	public function __construct($data = null) {
		parent::__construct(true, '', $data);
	}
};
