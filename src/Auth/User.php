<?php
namespace App\Auth;

use App\Library\AccessRepository;

final class User
{
	private $session;

	public function __construct() {
		$this->session = new \RKA\Session();
	}

	public function isAuthenticated() {
		return $this->session->get('authenticated', false);
	}

	public function authenticate($email, $password, $pdo) {
		$repo = new AccessRepository($pdo);
		$admin = $repo->getByEmail($email, true);

		if (!$admin) return false;

		if (password_verify($password, $admin['pass_hash'])) {
			$repo->updateLastLogin($admin['id']);

			$this->session->set('email', $admin['email']);
			if ($admin['lastlogin_date'] != null)
				$this->session->set('lastlogin_date', $admin['lastlogin_date']);
			if ($admin['lastlogin_ip'] != null)
				$this->session->set('lastlogin_ip', $admin['lastlogin_ip']);
			$this->session->set('authenticated', true);

			print('<strong>OK</strong>');

			return true;
		}

		return false;
	}

	public function logout() {
		$this->session->delete('email');
		$this->session->delete('lastlogin_date');
		$this->session->delete('lastlogin_ip');

		$this->session->set('authenticated', false);
		$this->session->delete('authenticated');
	}

	public function getLastLoginDate() {
		$lastLoginDate = $this->session->get('lastlogin_date', null);
		if ($lastLoginDate == null) {
			return 'Jamais';
		} else {
			$date = new \DateTime($lastLoginDate);
			return $date->format('d/m/Y à H:i:s');
		}
	}

	public function getLastLoginIp() {
		$lastLoginIp = $this->session->get('lastlogin_ip', null);
		if ($lastLoginIp == null) {
			return 'Aucune';
		} else {
			return $lastLoginIp;
		}
	}
}