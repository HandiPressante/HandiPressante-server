<?php
namespace App\Controller;

class BugTrackerController extends Controller {

	public function report($request, $response, $args) {
		$data = $request->getParsedBody();

		if ($data) {
			return $this->processForm($response, $data);
		}

		return $this->ci->view->render($response, 'BugTracker/report.html.twig');
	}

	public function acknowledgement($request, $response, $args) {
		return $this->ci->view->render($response, 'BugTracker/acknowledgement.html.twig');
	}

	private function processForm($response, $data) {
		$error = '';
		$version = '';
		$email = '';
		$observed_issue = '';
		$expected_behaviour = '';

		if (isset($data['version']))
			$version = filter_var($data['version'], FILTER_SANITIZE_STRING);

		if (!empty($version)) {

			if (isset($data['email'])) {
				$email = $data['email'];

				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

					if (isset($data['observed_issue']))
						$observed_issue = filter_var($data['observed_issue'], FILTER_SANITIZE_STRING);

					if (!empty($observed_issue)) {

						if (isset($data['expected_behaviour']))
							$expected_behaviour = filter_var($data['expected_behaviour'], FILTER_SANITIZE_STRING);

						if (!empty($expected_behaviour)) {

							$repo = $this->getRepository('BugTracker');
							$repo->add($version, $email, $observed_issue, $expected_behaviour);

							return $response->withRedirect($this->pathFor('bugtracker_acknowledgement'));
							
						} else {
							$error = 'Vous n\'avez pas indiqué le comportement correct attendu.';
						}
					} else {
						$error = 'Vous n\'avez pas expliqué le problème rencontré.';
					}
				} else {
					$error = 'L\'adresse e-mail fournie n\'est pas valide.';
				}
			} else {
				$error = 'Vous n\'avez pas fourni votre adresse e-mail.';
			}
		} else {
			$error = 'Vous n\'avez pas renseigné la version de l\'application sur laquelle vous avez rencontré le problème.';
		}

		return $this->ci->view->render($response, 'BugTracker/report.html.twig', ['error' => $error, 'data' => $data]);
	}

};
