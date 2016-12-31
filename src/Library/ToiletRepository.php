<?php
namespace App\Library;

class ToiletRepository extends Repository {

	const ACCESSIBILITY_FILTER_ADAPTED = 0;
	const ACCESSIBILITY_FILTER_NOT_ADAPTED = 1;
	const ACCESSIBILITY_FILTER_BOTH = 2;
	const ACCESSIBILITY_FILTERS = [
		self::ACCESSIBILITY_FILTER_ADAPTED, 
		self::ACCESSIBILITY_FILTER_NOT_ADAPTED,
		self::ACCESSIBILITY_FILTER_BOTH
		];

	const FEE_FILTER_FREE = 0;
	const FEE_FILTER_CHARGED = 1;
	const FEE_FILTER_BOTH = 2;
	const FEE_FILTERS = [
		self::FEE_FILTER_FREE, 
		self::FEE_FILTER_CHARGED,
		self::FEE_FILTER_BOTH
		];


	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, name, description, adapted, charged, latitude, longitude, cleanliness_avg, facilities_avg, accessibility_avg, rate_weight FROM toilets WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function exists($id) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $result['count'] > 0;
	}

	public function getInRange($lat, $long, $latRange, $longRange, $accessibilityFilter, $feeFilter) {
		$latMin = max(-90, $lat - $latRange);
		$latMax = min(90, $lat + $latRange);

		$longMin = $long - $longRange;
		$longMax = $long + $longRange;
		$onMeridian = false;
		
		if ($longMin < -180) {
			$longMin += 360;
			$onMeridian = true;
		} else if ($longMax > 180) {
			$longMax -= 360;
			$onMeridian = true;
		}

		$query = 'SELECT id, name, description, adapted, charged, latitude, longitude, cleanliness_avg, facilities_avg, accessibility_avg, rate_weight 
				FROM toilets WHERE (latitude >= :latMin AND latitude <= :latMax) AND ';

		if ($onMeridian) {
			$query .= '(longitude >= :longMin OR longitude <= :longMax)';
		} else {
			$query .= '(longitude >= :longMin AND longitude <= :longMax)';
		}

		if ($accessibilityFilter == self::ACCESSIBILITY_FILTER_ADAPTED) {
			$query .= ' AND adapted = 1';
		} elseif ($accessibilityFilter == self::ACCESSIBILITY_FILTER_NOT_ADAPTED) {
			$query .= ' AND adapted = 0';
		}

		if ($feeFilter == self::FEE_FILTER_CHARGED) {
			$query .= ' AND charged = 1';
		} elseif ($feeFilter == self::FEE_FILTER_FREE) {
			$query .= ' AND charged = 0';
		}

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':latMin', $latMin);
		$stmt->bindParam(':latMax', $latMax);
		$stmt->bindParam(':longMin', $longMin);
		$stmt->bindParam(':longMax', $longMax);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getNearby($lat, $long, $mincount, $maxcount, $maxdistance, $accessibilityFilter, $feeFilter) {
		$latRange = 1.0;
		$longRange = 1.0;
		$count = 0;
		$toFar = false;
		$candidateToilets = [];

		while ($count < $mincount && !$toFar && $latRange <= 90 && $longRange <= 180) {
			$candidateToilets = $this->getInRange($lat, $long, $latRange, $longRange, $accessibilityFilter, $feeFilter);
			$count = count($candidateToilets);

			foreach ($candidateToilets as $key => $toilet) {
				$distance = $this->distanceWGS84($lat, $long, $toilet['latitude'], $toilet['longitude']);
				if ($distance > $maxdistance) {
					$toFar = true;
				}

				$candidateToilets[$key]['distance'] = $distance;
			}

			$latRange *= 1.5;
			$longRange *= 1.5;
		}

		usort($candidateToilets, function ($toilet1, $toilet2) {
			if ($toilet1['distance'] == $toilet2['distance']) return 0;
			return ($toilet1['distance'] < $toilet2['distance']) ? -1 : 1;
		});

		$validCount = 0;
		while ($validCount < $count && $validCount < $maxcount && $candidateToilets[$validCount]['distance'] <= $maxdistance) {
			$validCount++;
		}

		return array_slice($candidateToilets, 0, $validCount);
	}

	public function getArea($northWestLat, $northWestLong, $southEastLat, $southEastLong, $accessibilityFilter, $feeFilter) {
		$query = 'SELECT id, name, description, adapted, charged, latitude, longitude, cleanliness_avg, facilities_avg, accessibility_avg, rate_weight 
				FROM toilets WHERE (latitude >= :latMin AND latitude <= :latMax) AND (longitude >= :longMin AND longitude <= :longMax)';

		if ($accessibilityFilter == self::ACCESSIBILITY_FILTER_ADAPTED) {
			$query .= ' AND adapted = 1';
		} elseif ($accessibilityFilter == self::ACCESSIBILITY_FILTER_NOT_ADAPTED) {
			$query .= ' AND adapted = 0';
		}

		if ($feeFilter == self::FEE_FILTER_CHARGED) {
			$query .= ' AND charged = 1';
		} elseif ($feeFilter == self::FEE_FILTER_FREE) {
			$query .= ' AND charged = 0';
		}

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':latMin', $southEastLat);
		$stmt->bindParam(':latMax', $northWestLat);
		$stmt->bindParam(':longMin', $northWestLong);
		$stmt->bindParam(':longMax', $southEastLong);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($name, $adapted, $charged, $description, $latitude, $longitude, $addedBy) {
		$stmt = $this->pdo->prepare('INSERT INTO toilets_data (name, adapted, charged, description, latitude, longitude, added_by, user_ip) VALUES (:name, :adapted, :charged, :description, :latitude, :longitude, :added_by, :user_ip)');
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(":adapted", $adapted);
		$stmt->bindParam(":charged", $charged);
		$stmt->bindParam(":description", $description);
		$stmt->bindParam(":latitude", $latitude);
		$stmt->bindParam(":longitude", $longitude);
		$stmt->bindParam(":added_by", $addedBy);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}

	public function update($id, $name, $adapted, $charged, $description, $latitude, $longitude) {
		$stmt = $this->pdo->prepare('UPDATE toilets_data SET name = :name, adapted = :adapted, charged = :charged, description = :description, latitude = :latitude, longitude = :longitude WHERE id = :id');
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(":adapted", $adapted);
		$stmt->bindParam(":charged", $charged);
		$stmt->bindParam(":description", $description);
		$stmt->bindParam(":latitude", $latitude);
		$stmt->bindParam(":longitude", $longitude);
		$stmt->bindParam(":id", $id);
		return $stmt->execute();
	}

	public function remove($id) {
		$stmt = $this->pdo->prepare('DELETE FROM toilets_data WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}


	public function hasAlreadyRated($userId, $toiletId) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM rates WHERE toilet_id = :toilet_id AND user_id = :user_id');
		$stmt->bindParam(":toilet_id", $toiletId);
		$stmt->bindParam(":user_id", $userId);
		$stmt->execute();

		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $result['count'] > 0;
	}

	public function addToiletRate($toiletId, $userId, $cleanlinessRate, $facilitiesRate, $accessibilityRate) {
		$stmt = $this->pdo->prepare('INSERT INTO rates (toilet_id, user_id, cleanliness, facilities, accessibility, user_ip) VALUES (:toilet_id, :user_id, :cleanliness, :facilities, :accessibility, :user_ip)');
		$stmt->bindParam(':toilet_id', $toiletId);
		$stmt->bindParam(":user_id", $userId);
		$stmt->bindParam(":cleanliness", $cleanlinessRate);
		$stmt->bindParam(":facilities", $facilitiesRate);
		$stmt->bindParam(":accessibility", $accessibilityRate);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}

	public function updateToiletRate($toiletId, $userId, $cleanlinessRate, $facilitiesRate, $accessibilityRate) {
		$stmt = $this->pdo->prepare('UPDATE rates SET cleanliness = :cleanliness, facilities = :facilities, accessibility = :accessibility, user_ip = :user_ip WHERE toilet_id = :toilet_id AND user_id = :user_id');
		$stmt->bindParam(':cleanliness', $cleanlinessRate);
		$stmt->bindParam(":facilities", $facilitiesRate);
		$stmt->bindParam(":accessibility", $accessibilityRate);
		$stmt->bindParam(":toilet_id", $toiletId);
		$stmt->bindParam(":user_id", $userId);
		$stmt->bindParam(":user_ip", $_SERVER['REMOTE_ADDR']);
		return $stmt->execute();
	}


	private function distanceWGS84($lat1, $long1, $lat2, $long2) {
		$radius = 6371;

		$rad_lat1 = ($lat1 * M_PI) / 180;
		$rad_long1 = ($long1 * M_PI) / 180;
		$rad_lat2 = ($lat2 * M_PI) / 180;
		$rad_long2 = ($long2 * M_PI) / 180;

		$x1 = $radius * cos($rad_lat1) * cos($rad_long1);
		$y1 = $radius * cos($rad_lat1) * sin($rad_long1);
		$z1 = $radius * sin($rad_lat1);

		$x2 = $radius * cos($rad_lat2) * cos($rad_long2);
		$y2 = $radius * cos($rad_lat2) * sin($rad_long2);
		$z2 = $radius * sin($rad_lat2);

		$dx = $x1 - $x2;
		$dy = $y1 - $y2;
		$dz = $z1 - $z2;

		$result_km = sqrt($dx*$dx + $dy*$dy + $dz*$dz);

		return $result_km * 1000;
	}
};
