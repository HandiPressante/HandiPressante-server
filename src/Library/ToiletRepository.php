<?php
namespace App\Library;

class ToiletRepository extends Repository {

	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, name, description, adapted, charged, lat84, long84, cleanliness_avg, facilities_avg, accessibility_avg, global_avg FROM toilets WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
};
