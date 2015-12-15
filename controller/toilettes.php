<?php
require_once '../config.inc.php';

function connect() {
	global $config;

	$pdo = null;
	try {
    	$pdo = new PDO('mysql:host=localhost;dbname=' . $config['database']['dbname'].';charset=UTF8', $config['database']['user'], $config['database']['pass']);
	} catch (PDOException $e) {
    	print "Error : " . $e->getMessage() . "<br/>";
    	die();
	}
	return $pdo;
}

function disconnect($dbh) {
	$dbh=null;

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
		
	}

	disconnect($db);
	if($succes)
		return $res;
	else
		return null;
}




function getToilettes($x,$y,$rangx,$rangy,$min,$max){
	if($rangx < 100)
		$rangx =100;
	if($rangy < 100)
		$rangy =100;
	$c = 0;
	for($i = 0 ; $c <= $min ; $i++ ){
		$tmp = getToilettesRange($x,$y,$rangx,$rangy);
		$c = count($tmp);
		$rangx = $rangx * 1.5 ;
		$rangy = $rangy * 1.5 ;
		if($rangx > 100000)
			break;
	}	


	for($i = 0 ; $i < $c ; $i++){
		$xcentre=$tmp[$i]['x93']-$x;
		$ycentre=$tmp[$i]['y93']-$y;
		$tmp[$i]['distance']=sqrt($xcentre * $xcentre +	$ycentre *	$ycentre);
		}


	if( $c > $max)
		$res = array_slice($res, 0, $max);

	return $res;
}




function getToilettesRange($x,$y,$rangx,$rangy){
	$db=connect();
	$succes=true;
	$x=(double)$x;
	$rangx=(double)$rangx;
	$y=(double)$y;
	$rangy=(double)$rangy;

	$maxx =	$x + $rangx;
	$minx = $x - $rangx;
	$maxy = $y + $rangy;
	$miny = $y - $rangy;

	$sql = "SELECT * FROM toilettes  WHERE x93 < :maxx AND x93 > :minx AND y93 < :maxy AND y93 > :miny";
	
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':minx', $minx);
		$stmt->bindParam(':maxx', $maxx);
		$stmt->bindParam(':maxy', $maxy);
		$stmt->bindParam(':miny', $miny);

		$stmt->execute();	
	
	} catch (PDOException $e) {
	    echo $e->getMessage();
		
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