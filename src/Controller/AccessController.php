<?php
namespace App\Controller;

class AccessController extends Controller {

	public function manage($request, $response, $args) {
		$repo = $this->getRepository('Access');
		$admins = $repo->getAll();

		return $this->ci->view->render($response, 'Access/manage.html.twig', ['admins' => $admins, 'token' => $this->getCsrfToken($request)]);
	}

	public function add($request, $response, $args) {
		$data = $request->getParsedBody();

		if (isset($data['email'])) {
			$email = filter_var($data['email'], FILTER_SANITIZE_STRING);
		
			$error = '';
			if ($this->createAccess($email, $error)) {
				$this->ci->flash->addMessage('Success', 'Compte créé avec succès, un mail automatique a été envoyé à l\'adresse <strong>' . $email . '</strong>.');
				return $response->withRedirect($this->pathFor('access_manage'));
			} else {
				return $this->ci->view->render($response, 'Access/add.html.twig', ['error' => $error, 'email' => $email, 'token' => $this->getCsrfToken($request)]);
			}
		}

		return $this->ci->view->render($response, 'Access/add.html.twig', ['token' => $this->getCsrfToken($request)]);
	}

	public function remove($request, $response, $args) {
		$id = (int)$args['id'];

		$repo = $this->getRepository('Access');
		$admin = $repo->get($id);

		if (!$admin) {
			$this->ci->flash->addMessage('Error', 'Ce compte n\'existe pas.');
			return $response->withRedirect($this->pathFor('access_manage'));
		}

		return $this->ci->view->render($response, 'Access/remove.html.twig', ['id' => $id, 'email' => $admin['email'], 'token' => $this->getCsrfToken($request)]);
	}

	public function removeConfirm($request, $response) {
		$data = $request->getParsedBody();

		if (isset($data['id'])) {
			$id = (int)$data['id'];

			$repo = $this->getRepository('Access');
			$repo->remove($id);

			$this->ci->flash->addMessage('Success', 'Le compte a été supprimé avec succès.');
		}

		return $response->withRedirect($this->pathFor('access_manage'));
	}

	private function createAccess($email, &$error) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$repo = $this->getRepository('Access');

			if (!$repo->emailExists($email)) {
				$result = $repo->add($email);
				$id = $result['id'];
				$passwordToken = $result['token'];

				$accountCreationEmail = $this->ci->view->fetch('Access/_account-creation.txt.twig', ['id' => $id, 'passwordToken' => $passwordToken]);

				$message = \Swift_Message::newInstance('Création de vos identifiants d\'administration')
					->setFrom(['no-reply@handipressante.fr' => 'HandiPressante'])
					->setTo([$email])
					->setBody($accountCreationEmail);

				if ($this->ci->mailer->send($message)) {
					return true;
				} else {
					$error = 'Une erreur a eu lieu lors de l\'envoi du mail de création du compte, veuillez réessayer ultérieurement.';
				}

			} else {
				$error = 'Cette adresse e-mail est déjà utilisée.';
			}
		} else {
			$error = 'Veuillez entrer une adresse e-mail valide.';
		}

		return false;
	}
};
