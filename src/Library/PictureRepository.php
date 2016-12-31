<?php
namespace App\Library;

class PictureRepository extends Repository {

	public function getByToilet($toiletId) {
		$stmt = $this->pdo->prepare('SELECT id, toilet_id, filename, postdate FROM pictures WHERE toilet_id = :toilet_id ORDER BY postdate DESC');
		$stmt->bindParam(":toilet_id", $toiletId);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($toiletId, $userId, $filename) {
		$stmt = $this->pdo->prepare('INSERT INTO pictures (toilet_id, user_id, filename, postdate) VALUES (:toilet_id, :user_id, :filename, NOW())');
		$stmt->bindParam(':toilet_id', $toiletId);
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(":filename", $filename);
		return $stmt->execute();
	}

	public function remove($pictureId) {
		$stmt = $this->pdo->prepare('DELETE FROM pictures WHERE id = :id');
		$stmt->bindParam(':id', $pictureId);
		$stmt->execute();
	}
};
