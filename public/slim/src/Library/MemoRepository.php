<?php
namespace App\Library;

class MemoRepository extends Repository {

	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, title, filename FROM memos WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getAll() {
		$stmt = $this->pdo->prepare('SELECT id, title, filename FROM memos ORDER BY title');
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function titleExists($title) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) FROM memos WHERE title = :title');
		$stmt->bindParam(':title', $title);
		$stmt->execute();
		$count = $stmt->fetchColumn();

		return $count > 0;
	}

	public function add($title, $filename)	{
		$stmt = $this->pdo->prepare('INSERT INTO memos (title, filename) VALUES (:title, :filename)');
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(":filename", $filename);
		$stmt->execute();
	}

	public function update($id, $newTitle, $newFilename) {
		$stmt = $this->pdo->prepare('UPDATE memos SET title = :title, filename = :filename WHERE id = :id');
		$stmt->bindParam(':title', $newTitle);
		$stmt->bindParam(':filename', $newFilename);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	public function remove($id) {
		$stmt = $this->pdo->prepare('DELETE FROM memos WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}
};
