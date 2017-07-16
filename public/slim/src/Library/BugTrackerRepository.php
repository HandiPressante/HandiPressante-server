<?php
namespace App\Library;

class BugTrackerRepository extends Repository {

	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, version, email, observed_issue, expected_behaviour, report_date, report_ip FROM reported_bugs WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getAll() {
		$stmt = $this->pdo->prepare('SELECT id, version, email, observed_issue, expected_behaviour, report_date, report_ip FROM reported_bugs ORDER BY report_date');
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($version, $email, $observed_issue, $expected_behaviour)	{
		$stmt = $this->pdo->prepare('INSERT INTO reported_bugs (version, email, observed_issue, expected_behaviour, report_date, report_ip) VALUES (:version, :email, :observed_issue, :expected_behaviour, NOW(), :report_ip)');
		$stmt->bindParam(':version', $version);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":observed_issue", $observed_issue);
		$stmt->bindParam(":expected_behaviour", $expected_behaviour);
		$stmt->bindParam(":report_ip", $_SERVER['REMOTE_ADDR']);
		$stmt->execute();
	}

	public function remove($id) {
		$stmt = $this->pdo->prepare('DELETE FROM reported_bugs WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}
};
