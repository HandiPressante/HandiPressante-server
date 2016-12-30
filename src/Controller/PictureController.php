<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;

class PictureController extends Controller {

	public function list($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Picture');
		$pictures = $repo->getByToilet($id);

		$apiResponse = new ApiSuccessResponse($pictures);

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

};
