<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;
use App\Library\ApiUserId;

class CommentController extends Controller {

	public function list($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Comment');
		$comments = $repo->getByToilet($id);

		$apiResponse = new ApiSuccessResponse($comments);

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function add($request, $response) {
		$data = $request->getParsedBody();

		if (!isset($data['user_id']) || 
			!isset($data['toilet_id']) ||
			!isset($data['username']) ||
			!isset($data['content']))
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
		$username = filter_var($data['username'], FILTER_SANITIZE_STRING);
		$content = filter_var($data['content'], FILTER_SANITIZE_STRING);

		$repoToilets = $this->getRepository('Toilet');

		if (!$repoToilets->exists($toiletId) ||
			empty($username) || (strlen($username) > 40) ||
			strlen($content) < 2 || (strlen($content) > 255))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$repoComments = $this->getRepository('Comment');
		
		if ($repoComments->add($toiletId, $userId->toString(), $username, $content)) {
			$apiResponse = new ApiSuccessResponse();
		} else {
			$apiResponse = new ApiErrorResponse('Enregistrement impossible.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

};
