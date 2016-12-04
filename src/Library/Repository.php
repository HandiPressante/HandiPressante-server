<?php
namespace App\Library;

class Repository {
	protected $pdo;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}
};