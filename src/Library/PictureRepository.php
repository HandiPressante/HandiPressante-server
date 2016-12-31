<?php
namespace App\Library;

class PictureRepository extends Repository {

	public function getByToilet($toiletId) {
		$stmt = $this->pdo->prepare('SELECT id, toilet_id, filename, postdate FROM pictures WHERE toilet_id = :toilet_id ORDER BY postdate DESC');
		$stmt->bindParam(":toilet_id", $toiletId);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function exists($id) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM pictures WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $result['count'] > 0;
	}

	public function add($toiletId, $userId, $filename) {
		$stmt = $this->pdo->prepare('INSERT INTO pictures (toilet_id, user_id, filename, postdate, user_ip) VALUES (:toilet_id, :user_id, :filename, NOW(), :user_ip)');
		$stmt->bindParam(':toilet_id', $toiletId);
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(":filename", $filename);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}

	public function remove($pictureId) {
		$stmt = $this->pdo->prepare('DELETE FROM pictures WHERE id = :id');
		$stmt->bindParam(':id', $pictureId);
		$stmt->execute();
	}

	public function addReport($pictureId, $userId) {
		$stmt = $this->pdo->prepare('INSERT IGNORE INTO picture_reports (picture_id, user_id, user_ip) VALUES (:picture_id, :user_id, :user_ip)');
		$stmt->bindParam(':picture_id', $pictureId);
		$stmt->bindParam(":user_id", $userId);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}
};
