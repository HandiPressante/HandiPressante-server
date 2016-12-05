<?php
namespace App\Controller;

class MemoController extends Controller {

	public function list($request, $response, $args) {
		$repo = $this->getRepository('Memo');
		$memoList = $repo->getAll();

		return $this->ci->json->render($response, $memoList);
	}

	public function manage($request, $response, $args) {
		$repo = $this->getRepository('Memo');
		$memoList = $repo->getAll();

		return $this->ci->view->render($response, 'Memo/manage.html.twig', ['memo_list' => $memoList, 'token' => $this->getCsrfToken($request)]);
	}

	public function add($request, $response, $args) {
		$data = $request->getParsedBody();

		$error = '';
		if (isset($data['title'])) {
			$title = filter_var($data['title'], FILTER_SANITIZE_STRING);

			$repo = $this->getRepository('Memo');
			if (!$repo->titleExists($title)) {
				$files = $request->getUploadedFiles();

				if (!empty($files['file'])) {
					$file = $files['file'];

					$filename = $this->saveFile($file, $error);
					if ($filename) {
						$repo->add($title, $filename);

						$this->ci->flash->addMessage('Success', 'Mémo médical ajouté avec succès.');
						return $response->withRedirect($this->pathFor('memo_manage'));
					}
				} else {
					$error = 'Vous n\'avez pas envoyé le fichier PDF du mémo médical.';
				}
			} else {
				$error = 'Ce titre est déjà utilisé par un autre mémo médical.';
			}
		} else {
			$error = 'Vous n\'avez pas choisi de titre pour le mémo médical.';
		}

		return $this->ci->view->render($response, 'Memo/add.html.twig', ['error' => $error, 'token' => $this->getCsrfToken($request)]);
	}

	public function remove($request, $response, $args) {
		$id = (int)$args['id'];

		$repo = $this->getRepository('Memo');
		$memo = $repo->get($id);

		if (!$memo) {
			$this->ci->flash->addMessage('Error', 'Ce mémo médical n\'existe pas.');
			return $response->withRedirect($this->pathFor('memo_manage'));
		}

		return $this->ci->view->render($response, 'Memo/remove.html.twig', ['id' => $id, 'title' => $memo['title'], 'token' => $this->getCsrfToken($request)]);
	}

	public function removeConfirm($request, $response) {
		$data = $request->getParsedBody();

		if (isset($data['id'])) {
			$id = (int)$data['id'];

			$repo = $this->getRepository('Memo');
			$memo = $repo->get($id);

			$uploadDir = $this->ci->settings['memo_upload_path'];
			unlink($uploadDir . $memo['filename']);
			
			$repo->remove($id);

			$this->ci->flash->addMessage('Success', 'Le mémo médical a été supprimé avec succès.');
		}

		return $response->withRedirect($this->pathFor('memo_manage'));
	}

	private function saveFile($file, &$error) {
		$uploadError = $file->getError();
		if ($file->getSize() > 1048576) {
			$uploadError = UPLOAD_ERR_FORM_SIZE;
		}

		if ($file->getError() === UPLOAD_ERR_OK) {
			$uploadDir = $this->ci->settings['memo_upload_path'];
			$filename = '';

			do {
				$filename = bin2hex(random_bytes(16)) . '.pdf';
			} while (file_exists($uploadDir . $filename));

			$file->moveTo($uploadDir . $filename);
			return $filename;
		} else {
			switch ($uploadError) {
				case UPLOAD_ERR_NO_FILE:
					$error = 'Aucun fichier n\'a été envoyé.';
					break;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = 'La taille du fichier envoyé dépasse la limite fixée à 1Mo (un mégaoctet).';
					break;
				default:
					$error = 'Une erreur inconnue s\'est produite lors de l\'envoi du fichier.';
			}
		}

		return null;
	}
};
