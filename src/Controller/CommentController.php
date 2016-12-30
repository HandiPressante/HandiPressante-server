<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;

class CommentController extends Controller {

	public function list($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Comment');
		$comments = $repo->getByToilet($id);

		$apiResponse = new ApiSuccessResponse($comments);

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

};
