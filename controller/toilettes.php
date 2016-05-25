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

	return json_encode(array('success' => $success, 'error' => $error));
}

function editToilet($id, $name, $accessible, $description, $latitude, $longitude)
{
	$db = connect();
	$success = true;
	$error = "";

	$sql = "UPDATE toilettes SET lieu = :name, pmr = :accessible, description = :description, lat84 = :latitude, long84 = :longitude WHERE id_toilettes = :id";

	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":id", $id);
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

	return json_encode(array('success' => $success, 'error' => $error));
}

function rateToilet($id, $uuid, $cleanliness, $facilities, $accessibility)
{
	$db = connect();
	$success = true;
	$error = "";

	if (hasRated($id, $uuid)) {
		$sql = "UPDATE notes SET proprete = :cleanliness, equipement = :facilities, accessibilite = :accessibility WHERE id_toilettes_notes = :id AND id_users_notes = :uuid";
	} else {
		$sql = "INSERT INTO notes (id_toilettes_notes, id_users_notes, proprete, equipement, accessibilite) VALUES (:id, :uuid, :cleanliness, :facilities, :accessibility)";
	}

	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":uuid", $uuid);
		$stmt->bindParam(":cleanliness", $cleanliness);
		$stmt->bindParam(":facilities", $facilities);
		$stmt->bindParam(":accessibility", $accessibility);

		if (!$stmt->execute()) {
			$success = false;
			$error = $stmt->errorInfo()[2];
		}
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$success = false;
	}

	disconnect($db);

	return array('success' => $success, 'error' => $error);
}

function hasRated($id, $uuid) {
	$db = connect();

	$sql = "SELECT COUNT(*) FROM notes WHERE id_toilettes_notes = :id AND id_users_notes = :uuid";

	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":uuid", $uuid);

		if (!$stmt->execute())
			return false;
		
		$result = $stmt->fetchColumn() > 0;
	} catch (PDOException $e) {
		$result = false;
	}

	disconnect($db);

	return $result;
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

function getToilet($id) {
	$db = connect();

	$success = true;
	$error = "";
	$result = null;

	$sql = "SELECT id, lieu, pmr, description, lat84, long84, moyenne_proprete, moyenne_equipement, moyenne_accessibilite FROM pinsListe WHERE id = :id";
	
	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);

		if (!$stmt->execute()) {
			$success = false;
			$error = $stmt->errorInfo()[2];
		} else {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}
	} catch (PDOException $e) {
		$success = false;
		$error = $e->getMessage();
	}

	disconnect($db);

	return array('success' => $success, 'error' => $error, 'result' => $result);
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

/*
 * Add photo
 */

DEFINE ('ERROR_OK', 0);
DEFINE ('ERROR_UUID', 1);
DEFINE ('ERROR_TOILET_ID', 2);
DEFINE ('ERROR_FILE', 3);
DEFINE ('ERROR_SQL', 4);

function addPhoto($toilet_id, $uuid, $filename)
{
	$db = connect();
	$success = true;

	$sql = 'INSERT INTO photos (toilet_id, user_id, filename, postdate) VALUES (:toilet_id, :uuid, :filename, NOW())';

	try {
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':toilet_id', $toilet_id);
		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':filename', $filename);

		if (!$stmt->execute()) {
			$success = false;
			//$error = $stmt->errorInfo()[2];
		}
	} catch (PDOException $e) {
		//$error = $e->getMessage();
		$success = false;
	}

	disconnect($db);

	return $success;
}

function savePhoto($uuid, $toilet_id, $photo) {

	if (empty($uuid))
    	return ERROR_UUID;

    $tid = (int) $toilet_id;
	if ($tid == 0)
    	return ERROR_TOILET_ID;

    if (empty($photo))
    	return ERROR_FILE;

    if ($photo->getError() === UPLOAD_ERR_OK) {
    	$photoDir = 'images/photos/' . $toilet_id . '/';
    	if (!file_exists($photoDir)) {
    		mkdir($photoDir, 0777, true);
    	}

	    $filename = 'JPEG_' . date('Ymd_His') . '.jpg';
	    $photo->moveTo($photoDir . $filename);

	    if (!addPhoto($toilet_id, $uuid, $filename)) {
	    	unlink($photoDir . $filename);
	    	return ERROR_SQL;
	    }
	} else {
		return ERROR_FILE;
	}

	return ERROR_OK;
}

function getPhotos($toilet_id)
{
	$db = connect();
	$result = null;

	try {
		$stmt = $db->prepare('SELECT id, toilet_id, user_id, filename, postdate FROM photos WHERE toilet_id = :toilet_id ORDER BY postdate');
		$stmt->bindParam(':toilet_id', $toilet_id);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
	    echo $e->getMessage();
	}

	disconnect($db);
	return $result;
}

?>