<?php
namespace App\Library;

class CommentRepository extends Repository {

	public function getByToilet($toiletId) {
		$stmt = $this->pdo->prepare('SELECT id, username, content, postdate FROM comments WHERE toilet_id = :toilet_id ORDER BY postdate DESC');
		$stmt->bindParam(":toilet_id", $toiletId);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($toiletId, $userId, $username, $content) {
		$stmt = $this->pdo->prepare('INSERT INTO comments (toilet_id, user_id, username, content, postdate, user_ip) VALUES (:toilet_id, :user_id, :username, :content, NOW(), :user_ip)');
		$stmt->bindParam(':toilet_id', $toiletId);
		$stmt->bindParam(":user_id", $userId);
		$stmt->bindParam(":username", $username);
		$stmt->bindParam(":content", $content);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}

	public function update($commentId, $username, $content) {
		$stmt = $this->pdo->prepare('UPDATE comments SET username = :username, content = :content WHERE id = :id');
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(":content", $content);
		$stmt->bindParam(":id", $commentId);
		return $stmt->execute();
	}

	public function remove($commentId) {
		$stmt = $this->pdo->prepare('DELETE FROM comments WHERE id = :id');
		$stmt->bindParam(':id', $commentId);
		$stmt->execute();
	}
};
