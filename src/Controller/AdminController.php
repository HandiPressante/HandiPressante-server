<?php
namespace App\Controller;

use App\Auth\User;

class AdminController extends Controller {
	private $user;

	public function __construct($container) {
		parent::__construct($container);
		$this->user = new User();
	}

	public function index($request, $response, $args) {
		return $this->ci->view->render($response, 'Admin/index.html.twig', ['user' => $this->user]);
	}

	public function login($request, $response, $args) {
		if ($this->user->isAuthenticated()) {
			$this->ci->flash->addMessage('Info', 'Déjà connecté(e).');
			return $response->withRedirect($this->pathFor('admin_index'));
		}

		$error = false;
		$data = $request->getParsedBody();
		if (isset($data['email']) && isset($data['password'])) {
			$login_data = [];
			$login_data['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
			$login_data['password'] = $data['password'];

			$success = $this->user->authenticate($login_data['email'], $login_data['password'], $this->ci->pdo);

			if ($success) {
				$this->ci->flash->addMessage('Success', 'Connecté(e) avec succès.');
				return $response->withRedirect($this->pathFor('admin_index'));
			} else {
				$error = true;
			}
		}

		return $this->ci->view->render($response, 'Admin/login.html.twig', ['error' => $error]);
	}

	public function logout($request, $response, $args) {
		$this->user->logout();
		$this->ci->flash->addMessage('Success', 'Déconnecté(e) avec succès.');
		return $response->withRedirect($this->pathFor('admin_login'));
	}

	public function setPassword($request, $response, $args) {
		if ($this->user->isAuthenticated()) {
			$this->user->logout();
		}

		$id = (int)$args['id'];
		$token = filter_var($args['token'], FILTER_SANITIZE_STRING);

		$repo = $this->getRepository('Access');
		$error = '';
		$printForm = true;

		if (!$repo->isPasswordTokenValid($id, $token)) {
			$error = 'Désolé, le jeton d\'initialisation du mot de passe n\'est plus valide. <br />' .
				'<a href="' . $this->pathFor('admin_passwordreset') . '">Cliquez ici</a> pour recevoir un nouveau jeton par mail.';
			$printForm = false;
		}

		return $this->ci->view->render($response, 'Admin/set-password.html.twig', ['id' => $id, 'passwordToken' => $token, 'error' => $error, 'printForm' => $printForm]);
	}

	public function setPasswordPost($request, $response) {
		if ($this->user->isAuthenticated()) {
			$this->user->logout();
		}

		$data = $request->getParsedBody();
		if (!isset($data['id']) || !isset($data['token'])) {
			$this->ci->flash->addMessage('Error', 'Une erreur inconnue s\'est produite.');
			return $response->withRedirect($this->pathFor('admin_passwordreset'));
		} 

		$id = (int)$data['id'];
		$token = filter_var($data['token'], FILTER_SANITIZE_STRING);

		$error = '';
		$printForm = true;

		if (isset($data['password']) && isset($data['passwordConfirm'])) {
			$repo = $this->getRepository('Access');

			if ($repo->isPasswordTokenValid($id, $token)) {
				$password = $data['password'];
				$passwordConfirm = $data['passwordConfirm'];

				if ($password == $passwordConfirm) {
					if (strlen($password) >= 8) {
						$repo->changePassword($id, $password);

						$this->ci->flash->addMessage('Success', 'Votre mot de passe a bien été mis à jour.');
						return $response->withRedirect("/admin/login");
					} else {
						$error = 'Le mot de passe doit contenir au moins 8 caractères.';
					}
				} else {
					$error = 'Les mots de passe ne sont pas identiques.';
				}
			} else {
				$error = 'Désolé, le jeton d\'initialisation du mot de passe n\'est plus valide. <br />' .
					'<a href="' . $this->pathFor('admin_passwordreset') . '">Cliquez ici</a> pour recevoir un nouveau jeton par mail.';
				$printForm = false;
			}
		} else {
			$error = 'Veuillez remplir tous les champs du formulaire.';
		}

		return $this->ci->view->render($response, 'Admin/set-password.html.twig', ['id' => $id, 'passwordToken' => $token, 'error' => $error, 'printForm' => true]);
	}

	public function requirePasswordReset($request, $response, $args) {
		$data = $request->getParsedBody();
		if (!isset($data['email'])) {
			return $this->ci->view->render($response, 'Admin/require-password-reset.html.twig');
		}

		$email = filter_var($data['email'], FILTER_SANITIZE_STRING);
		$error = '';
		$sent = false;

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// If there is no account bound to the given email, we don't inform the user, to prevent targeting.
			$sent = true;

			$repo = $this->getRepository('Access');
			$admin = $repo->getByEmail($email);

			if ($admin) { // check if null
				$token = $repo->requirePasswordReset($admin['id']);

				$passwordResetEmail = $this->ci->view->fetch('Admin/_password-reset.txt.twig', ['id' => $admin['id'], 'token' => $token]);

				$message = \Swift_Message::newInstance('Réinitialisation du mot de passe')
					->setFrom(['no-reply@handipressante.fr' => 'HandiPressante'])
					->setTo([$admin['email']])
					->setBody($passwordResetEmail);

				if (!$this->ci->mailer->send($message)) {
					$sent = false;
					$error = 'Une erreur a eu lieu lors de l\'envoi du mail de réinitialisation, veuillez réessayer ultérieurement.';
				}
			}
		} else {
			$error = 'L\'adresse que vous avez entré n\'est pas une adresse e-mail valide.';
		}

		return $this->ci->view->render($response, 'Admin/require-password-reset.html.twig', ['sent' => $sent, 'error' => $error]);
	}
};
