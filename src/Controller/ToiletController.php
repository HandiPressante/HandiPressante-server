<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;

class ToiletController extends Controller {

	public function get($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Toilet');
		$toilet = $repo->get($id);

		if ($toilet) $apiResponse = new ApiSuccessResponse($toilet);
		else $apiResponse = new ApiErrorResponse("Ces toilettes n'existent pas.");

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function getNearby($request, $response, $args) {
		$lat = (double) $args['lat'];
		$long = (double) $args['long'];
		$mincount = (int) $args['mincount'];
		$maxcount = (int) $args['maxcount'];
		$maxdistance = (int) $args['maxdistance'];

		if ($this->validateLatLong($lat, $long) &&
			$mincount > 0 &&
			$maxcount > 0 &&
			$maxdistance > 0)
		{
			$repo = $this->getRepository('Toilet');
			$toilets = $repo->getNearby($lat, $long, $mincount, $maxcount, $maxdistance);
			$apiResponse = new ApiSuccessResponse($toilets);
		}
		else
		{
			$apiResponse = new ApiErrorResponse("Requête invalide.");
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	private function validateLatLong($lat, $long) {
		return ($lat >= -90) && ($lat <= 90) && ($long >= -180) && ($long <= 180);
	}
};
