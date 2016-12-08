<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;

class ToiletController extends Controller {

	public function get($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Toilet');
		$toilet = $repo->get($id);

		if ($toilet)
			$apiResponse = new ApiSuccessResponse($toilet);
		else
			$apiResponse = new ApiErrorResponse("Ces toilettes n'existent pas.");

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

};
