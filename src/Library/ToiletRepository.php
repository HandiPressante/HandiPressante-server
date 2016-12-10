<?php
namespace App\Library;

class ToiletRepository extends Repository {

	public function get($id) {
		$stmt = $this->pdo->prepare('SELECT id, name, description, adapted, charged, lat84, long84, cleanliness_avg, facilities_avg, accessibility_avg FROM toilets WHERE id = :id');
		$stmt->bindParam(":id", $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getInRange($lat, $long, $latRange, $longRange) {
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

		$query = 'SELECT id, name, description, adapted, charged, lat84, long84, cleanliness_avg, facilities_avg, accessibility_avg 
				FROM toilets WHERE (lat84 >= :latMin AND lat84 <= :latMax) AND ';

		if ($onMeridian) {
			$query .= '(long84 >= :longMin OR long84 <= :longMax)';
		} else {
			$query .= '(long84 >= :longMin AND long84 <= :longMax)';
		}

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':latMin', $latMin);
		$stmt->bindParam(':latMax', $latMax);
		$stmt->bindParam(':longMin', $longMin);
		$stmt->bindParam(':longMax', $longMax);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getNearby($lat, $long, $mincount, $maxcount, $maxdistance) {
		$latRange = 1.0;
		$longRange = 1.0;
		$count = 0;
		$toFar = false;
		$candidateToilets = [];

		while ($count < $mincount && !$toFar && $latRange <= 90 && $longRange <= 180) {
			$candidateToilets = $this->getInRange($lat, $long, $latRange, $longRange);
			$count = count($candidateToilets);

			foreach ($candidateToilets as $key => $toilet) {
				$distance = $this->distanceWGS84($lat, $long, $toilet['lat84'], $toilet['long84']);
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

	public function getArea($northWestLat, $northWestLong, $southEastLat, $southEastLong) {
		$query = 'SELECT id, name, description, adapted, charged, lat84, long84, cleanliness_avg, facilities_avg, accessibility_avg 
				FROM toilets WHERE (lat84 >= :latMin AND lat84 <= :latMax) AND (long84 >= :longMin AND long84 <= :longMax)';

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':latMin', $southEastLat);
		$stmt->bindParam(':latMax', $northWestLat);
		$stmt->bindParam(':longMin', $northWestLong);
		$stmt->bindParam(':longMax', $southEastLong);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
