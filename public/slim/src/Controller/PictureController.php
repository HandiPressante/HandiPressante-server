<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;
use App\Library\ApiUserId;

class PictureController extends Controller {

	public function list($request, $response, $args) {
		$id = (int) $args['id'];

		$userId = new ApiUserId(filter_var($args['user_id'], FILTER_SANITIZE_STRING));
		if (!$userId->isValid())
		{
			$apiResponse = new ApiErrorResponse('Identifiant invalide, essayez de redémarrer l\'application.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$repo = $this->getRepository('Picture');
		$pictures = $repo->getByToilet($id, $userId->toString());

		$apiResponse = new ApiSuccessResponse($pictures);

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function add($request, $response) {
		$data = $request->getParsedBody();
		$files = $request->getUploadedFiles();

		if (!isset($data['user_id']) || 
			!isset($data['toilet_id']) || 
			!isset($files['picture']))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}


		$userId = new ApiUserId(filter_var($data['user_id'], FILTER_SANITIZE_STRING));
		if (!$userId->isValid())
		{
			$apiResponse = new ApiErrorResponse('Identifiant invalide, essayez de redémarrer l\'application.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}
		
		$toiletId = (int) $data['toilet_id'];
		$picture = $files['picture'];

		$repoToilets = $this->getRepository('Toilet');

		if (!$repoToilets->exists($toiletId) || empty($picture))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$error = '';
		$filename = $this->savePicture($toiletId, $picture, $error);

		if ($filename) {
			$repoPictures = $this->getRepository('Picture');
			$repoPictures->add($toiletId, $userId->toString(), $filename);
			$apiResponse = new ApiSuccessResponse();
		} else {
			$apiResponse = new ApiErrorResponse($error);
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	private function savePicture($toiletId, $picture, &$error) {
		$uploadError = $picture->getError();
		if ($picture->getSize() > 5242880) {
			$uploadError = UPLOAD_ERR_FORM_SIZE;
		}

		if ($picture->getError() === UPLOAD_ERR_OK) {
			$uploadDir = $this->ci->settings['picture_upload_path'] . $toiletId . '/';
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}

			$filename = '';

			do {
				$filename = bin2hex(random_bytes(16)) . '.jpg';
			} while (file_exists($uploadDir . $filename));

			$picture->moveTo($uploadDir . $filename);
			return $filename;
		} else {
			switch ($uploadError) {
				case UPLOAD_ERR_NO_FILE:
					$error = 'Aucun fichier n\'a été envoyé.';
					break;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = 'La taille du fichier envoyé dépasse la limite fixée à 5Mo (cinq mégaoctet).';
					break;
				default:
					$error = 'Une erreur inconnue s\'est produite lors de l\'envoi du fichier.';
			}
		}

		return null;
	}

	public function report($request, $response) {
		$data = $request->getParsedBody();

		if (!isset($data['user_id']) || 
			!isset($data['picture_id']))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$userId = new ApiUserId(filter_var($data['user_id'], FILTER_SANITIZE_STRING));
		if (!$userId->isValid())
		{
			$apiResponse = new ApiErrorResponse('Identifiant invalide, essayez de redémarrer l\'application.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}
		
		$pictureId = (int) $data['picture_id'];

		$repo = $this->getRepository('Picture');
		if (!$repo->exists($pictureId))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		if ($repo->addReport($pictureId, $userId->toString())) {
			$apiResponse = new ApiSuccessResponse();
		} else {
			$apiResponse = new ApiErrorResponse('Signalement impossible.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

};
