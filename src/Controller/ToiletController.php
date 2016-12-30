<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;

class ToiletController extends Controller {

	public function get($request, $response, $args) {
		$id = (int) $args['id'];

		$repo = $this->getRepository('Toilet');
		$toilet = $repo->get($id);

		if ($toilet) $apiResponse = new ApiSuccessResponse([$toilet]);
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
			$apiResponse = new ApiErrorResponse('Requête invalide.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function getArea($request, $response, $args) {
		$northWestLat = (double) $args['lat_nw'];
		$northWestLong = (double) $args['long_nw'];
		$southEastLat = (double) $args['lat_se'];
		$southEastLong = (double) $args['long_se'];

		if ($this->validateLatLong($northWestLat, $northWestLong) &&
			$this->validateLatLong($southEastLat, $southEastLong))
		{
			$repo = $this->getRepository('Toilet');
			$toilets = $repo->getArea($northWestLat, $northWestLong, $southEastLat, $southEastLong);
			$apiResponse = new ApiSuccessResponse($toilets);
		}
		else
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function save($request, $response) {
		$data = $request->getParsedBody();

		if (!isset($data['uuid']) || 
			!isset($data['toilet_name']) ||
			!isset($data['toilet_adapted']) ||
			!isset($data['toilet_charged']) ||
			!isset($data['toilet_description']) ||
			!isset($data['toilet_latitude']) ||
			!isset($data['toilet_longitude']))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$uuid = strtolower(filter_var($data['uuid'], FILTER_SANITIZE_STRING));
		
		$name = filter_var($data['toilet_name'], FILTER_SANITIZE_STRING);
		$adapted = (bool) $data['toilet_adapted'];
		$charged = (bool) $data['toilet_charged'];
		$description = filter_var($data['toilet_description'], FILTER_SANITIZE_STRING);
		$latitude = (double) $data['toilet_latitude'];
		$longitude = (double) $data['toilet_longitude'];

		if (empty($uuid) || // todo : check uuid format with regexp
			empty($name) || (strlen($name) > 40) ||
			!$this->validateLatLong($latitude, $longitude))
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		$repo = $this->getRepository('Toilet');
		$success = false;

		$update = isset($data['toilet_id']) && ((int)$data['toilet_id'] > 0);
		if ($update)
		{
			$id = (int) $data['toilet_id'];
			$success = $repo->update($id, $name, $adapted, $charged, $description, $latitude, $longitude);
		}
		else
		{
			$success = $repo->add($name, $adapted, $charged, $description, $latitude, $longitude, $uuid);
		}

		if ($success) {
			$apiResponse = new ApiSuccessResponse();
		} else {
			$apiResponse = new ApiErrorResponse('Enregistrement impossible.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	private function validateLatLong($lat, $long) {
		return ($lat >= -90.0) && ($lat <= 90.0) && ($long >= -180.0) && ($long <= 180.0);
	}
};
