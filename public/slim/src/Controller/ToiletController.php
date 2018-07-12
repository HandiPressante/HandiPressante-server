<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;
use App\Library\ApiErrorResponse;
use App\Library\ApiUserId;
use App\Library\ToiletRepository;

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
		$accessibilityFilter = (int) $args['accessibility_filter'];
		$feeFilter = (int) $args['fee_filter'];

		if ($this->validateLatLong($lat, $long) &&
			$mincount > 0 &&
			$maxcount > 0 &&
			$maxdistance > 0 &&
			in_array($accessibilityFilter, ToiletRepository::ACCESSIBILITY_FILTERS) &&
			in_array($feeFilter, ToiletRepository::FEE_FILTERS))
		{
			$repo = $this->getRepository('Toilet');
			$toilets = $repo->getNearby($lat, $long, $mincount, $maxcount, $maxdistance, $accessibilityFilter, $feeFilter);
			$apiResponse = new ApiSuccessResponse($toilets);

			$repoStats = $this->getRepository('Statistics');
			$repoStats->addNewRequest();
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
		$accessibilityFilter = (int) $args['accessibility_filter'];
		$feeFilter = (int) $args['fee_filter'];

		if ($this->validateLatLong($northWestLat, $northWestLong) &&
			$this->validateLatLong($southEastLat, $southEastLong) &&
			in_array($accessibilityFilter, ToiletRepository::ACCESSIBILITY_FILTERS) &&
			in_array($feeFilter, ToiletRepository::FEE_FILTERS))
		{
			$repo = $this->getRepository('Toilet');
			$toilets = $repo->getArea($northWestLat, $northWestLong, $southEastLat, $southEastLong, $accessibilityFilter, $feeFilter);
			$apiResponse = new ApiSuccessResponse($toilets);

			$repoStats = $this->getRepository('Statistics');
			$repoStats->addNewRequest();
		}
		else
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function rate($request, $response) {
		$data = $request->getParsedBody();

		if (!isset($data['user_id']) || 
			!isset($data['toilet_id']) ||
			!isset($data['toilet_cleanliness']) ||
			!isset($data['toilet_facilities']) ||
			!isset($data['toilet_accessibility']))
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
		$cleanlinessRate = (int) $data['toilet_cleanliness'];
		$facilitiesRate = (int) $data['toilet_facilities'];
		$accessibilityRate = (int) $data['toilet_accessibility'];

		$repo = $this->getRepository('Toilet');

		if (!$repo->exists($toiletId) ||
			$cleanlinessRate < 0 || $cleanlinessRate > 5 ||
			$facilitiesRate < 0 || $facilitiesRate > 5 ||
			$accessibilityRate < 0 || $accessibilityRate > 5)
		{
			$apiResponse = new ApiErrorResponse('Requête invalide.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}

		if ($repo->hasAlreadyRated($userId->toString(), $toiletId)) {
			$success = $repo->updateToiletRate($toiletId, $userId->toString(), $cleanlinessRate, $facilitiesRate, $accessibilityRate);
		} else {
			$success = $repo->addToiletRate($toiletId, $userId->toString(), $cleanlinessRate, $facilitiesRate, $accessibilityRate);
		}

		if ($success) {
			$apiResponse = new ApiSuccessResponse();
		} else {
			$apiResponse = new ApiErrorResponse('Enregistrement impossible.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	public function save($request, $response) {
		$data = $request->getParsedBody();

		if (!isset($data['user_id']) || 
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


		$userId = new ApiUserId(filter_var($data['user_id'], FILTER_SANITIZE_STRING));
		if (!$userId->isValid())
		{
			$apiResponse = new ApiErrorResponse('Identifiant invalide, essayez de redémarrer l\'application.');
			return $this->ci->json->render($response, $apiResponse->toArray());
		}
		
		$name = $data['toilet_name'];
		$adapted = (bool) $data['toilet_adapted'];
		$charged = (bool) $data['toilet_charged'];
		$description = $data['toilet_description'];
		$latitude = (double) $data['toilet_latitude'];
		$longitude = (double) $data['toilet_longitude'];

		if (empty($name) || (strlen($name) > 40) ||
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
			$success = $repo->add($name, $adapted, $charged, $description, $latitude, $longitude, $userId->toString());
		}

		if ($success) {
			if ($update) {
				$apiResponse = new ApiSuccessResponse();
			} else {
				$apiResponse = new ApiSuccessResponse(array('toilet_id' => $repo->lastInsertId()));
			}
		} else {
			$apiResponse = new ApiErrorResponse('Enregistrement impossible.');
		}

		return $this->ci->json->render($response, $apiResponse->toArray());
	}

	private function validateLatLong($lat, $long) {
		return ($lat >= -90.0) && ($lat <= 90.0) && ($long >= -180.0) && ($long <= 180.0);
	}
};
