<?php
require_once '../database.php';

function addToilet($name, $accessible, $description, $latitude, $longitude)
{
	$db = connect();
	$success = true;
	$error = "";

	$sql = "INSERT INTO toilettes (lieu, pmr, description, lat84, long84) VALUES (:name, :accessible, :description, :latitude, :longitude)";

	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":accessible", $accessible);
		$stmt->bindParam(":description", $description);
		$stmt->bindParam(":latitude", $latitude);
		$stmt->bindParam(":longitude", $longitude);

		if (!$stmt->execute()) {
			$success = false;
			$error = $stmt->errorInfo()[2];
		}
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$success = false;
	}

	disconnect($db);

	$data = $name . ';' . $accessible . ';' . $description . ';' . $latitude . ';' . $longitude;
	return json_encode(array('success' => $success, 'error' => $error, 'data' => $data));
}

function getFiches($id){
	$db=connect();
	$succes=true;
	$sql = "SELECT * FROM fiches WHERE id = :id ";
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':id', $id);
	
		$stmt->execute();
		
		$tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$res=$tmp[0];
		$res['moyenne']=($res['moyenne_proprete']+$res['moyenne_equipement']+$res['moyenne_accessibilite'])/3;

	} catch (PDOException $e) {
	    echo $e->getMessage();
		 $succes=false;
	}

	disconnect($db);
	if($succes)
		return $res;
	else
		return null;
}

function distanceWGS84($long1, $lat1, $long2, $lat2){

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

function getPinsListe($long,$lat,$min,$max,$distancemax){
	$longrang =1.0;
	$latrang =1.0;
	$c = 0;
	$toFar =false;
	$restmp=array();

	for($i = 0 ; $c <= $min ; $i++ ){
		$restmp = getToilettesRange($long,$lat,$longrang,$latrang);
		$c = count($restmp);
		for($i = 0 ; $i < $c ; $i++){
			$restmp[$i]['distance']= distanceWGS84($long,$lat,$restmp[$i]['long84'],$restmp[$i]['lat84']);
			if ($restmp[$i]['distance'] > $distancemax ){
				$toFar=true;
			}
		}
		$longrang = $longrang * 1.5 ;
		$latrang = $latrang * 1.5 ;

		if($toFar)
			break;
	}	

	$res=array();
	$index=0;
	if($toFar)
		for($i = 0 ; $i < $c ; $i++ ){
			if($restmp[$i]['distance'] < $distancemax){
				$res[$index]=$restmp[$i];
				$index++;
			}
		}
		else

			$res=$restmp;

	$c=count($res);
	if($c==0)
		return null;

	$id_distance;
	$sorted;
	for($i=0 ; $i < $c ; $i++){
		$id_distance[$i]=$res[$i]['distance'];
	}
	
	asort($id_distance);

	$i=0;
	foreach ($id_distance as $key => $value){
		$sorted[$i]=$res[$key];
		$i++;
	}


	if( $c > $max)
		$sorted = array_slice($sorted, 0, $max);

	return $sorted;
}


function getPinsCarte($longtopl,$lattopl,$longbotr,$latbotr){
	$db=connect();
	$succes=true;
	
		$longbotr=(double)$longbotr;
		$longtopl=(double)$longtopl;
		$lattopl=(double)$lattopl;
		$latbotr=(double)$latbotr;

		echo $longtopl." ". $lattopl." ". $longbotr." ". $latbotr;

	$sql = "SELECT * FROM pinsListe WHERE long84 > :xtopl AND lat84 < :ytopl AND long84 < :xbotr AND  lat84 > :ybotr";
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':xbotr', $longbotr);
		$stmt->bindParam(':xtopl', $longtopl);
		$stmt->bindParam(':ytopl', $lattopl);
		$stmt->bindParam(':ybotr', $latbotr);


		$stmt->execute();	

	} catch (PDOException $e) {
	    echo $e->getMessage();
		 $succes=false;
	}

	disconnect($db);
	if($succes)
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	else
		return null;
}


function getToilettesRange($long,$lat,$longrang,$latrang){
	$db=connect();
	$succes=true;
	$long=(double)$long;
	$longrang=(double)$longrang;
	$lat=(double)$lat;
	$latrang=(double)$latrang;

	$maxx =	$long + $longrang;
	$minx = $long - $longrang;
	$maxy = $lat + $latrang;
	$miny = $lat - $latrang;

	$sql = "SELECT * FROM pinsListe  WHERE long84 < :maxx AND long84 > :minx AND lat84 < :maxy AND lat84 > :miny";
	
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':minx', $minx);
		$stmt->bindParam(':maxx', $maxx);
		$stmt->bindParam(':maxy', $maxy);
		$stmt->bindParam(':miny', $miny);

		$stmt->execute();	
	
	} catch (PDOException $e) {
	    echo $e->getMessage();
		 $succes=false;
	}

	disconnect($db);
	if($succes)
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	else
		return null;
}



function getImages($id) {
	$db=connect();
    $succes=true;
	$sql = "SELECT image FROM images  WHERE id_images = :id ";
	
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':id', $id);
	
		$stmt->execute();
	
	} catch (PDOException $e) {
	    echo $e->getMessage();
	    $succes=false;
	}

	disconnect($db);
	if($succes)
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	else
		return null;
}


?>