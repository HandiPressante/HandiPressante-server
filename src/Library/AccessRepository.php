<?php
namespace App\Library;

class AccessRepository extends Repository {

	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, email FROM admins WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getByEmail($email, $full = false) {
		$stmt = null;
		if ($full) {
			$stmt = $this->pdo->prepare('SELECT id, email, pass_hash, lastlogin_date, lastlogin_ip FROM admins WHERE email = :email LIMIT 1');
		} else {
			$stmt = $this->pdo->prepare('SELECT id, email FROM admins WHERE email = :email LIMIT 1');
		}

		$stmt->bindParam(":email", $email);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getAll() {
		$stmt = $this->pdo->prepare('SELECT id, email FROM admins ORDER BY id DESC');
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function emailExists($email) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email');
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$count = $stmt->fetchColumn();

		return $count > 0;
	}

	public function add($email)	{
		$token = $this->generatePasswordToken();

		$stmt = $this->pdo->prepare('INSERT INTO admins (email, token, token_timestamp) VALUES (:email, :token, :token_timestamp)');
		$stmt->bindParam(":token", $token['value']);
		$stmt->bindParam(":token_timestamp", $token['timestamp']);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		return ['id' => $this->pdo->lastInsertId(), 'token' => $token['value']];
	}

	public function remove($id) {
		$stmt = $this->pdo->prepare('DELETE FROM admins WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	public function updateLastLogin($id) {
		$stmt = $this->pdo->prepare('UPDATE admins SET lastlogin_date = NOW(), lastlogin_ip = :ip WHERE id = :id');
		$stmt->bindParam(":ip", $_SERVER['REMOTE_ADDR']);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
	}

	public function changePassword($id, $newPassword) {
		$passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

		$stmt = $this->pdo->prepare('UPDATE admins SET pass_hash = :pass_hash, token = NULL, token_timestamp = NULL WHERE id = :id');
		$stmt->bindParam(':pass_hash', $passwordHash);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	public function requirePasswordReset($id) {
		$token = $this->generatePasswordToken();

		$stmt = $this->pdo->prepare('UPDATE admins SET token = :token, token_timestamp = :token_timestamp WHERE id = :id');
		$stmt->bindParam(":token", $token['value']);
		$stmt->bindParam(":token_timestamp", $token['timestamp']);
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $token['value'];
	}

	public function isPasswordTokenValid($id, $token) {
		$stmt = $this->pdo->prepare('SELECT token, token_timestamp FROM admins WHERE id = :id LIMIT 1');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		if ($admin = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$maxValidity = 3600 * 48; // 48 hours
			$elapsedTime = time() - $admin['token_timestamp'];

			if (strlen($admin['token']) > 0 && $admin['token'] == $token && $elapsedTime <= $maxValidity) {
				return true;
			}
		}

		return false;
	}

	private function generatePasswordToken() {
		$value = bin2hex(random_bytes(32));
		$timestamp = time();

		return ['value' => $value, 'timestamp' => $timestamp];
	}
};