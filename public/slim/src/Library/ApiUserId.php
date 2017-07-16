<?php
namespace App\Library;

class ApiUserId {
	private $uuid;

	public function __construct($uuid) {
		$this->uuid = strtolower($uuid);
	}

	public function isValid() {
		return preg_match('/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/', $this->uuid);
	}

	public function toString() {
		return $this->uuid;
	}
};
