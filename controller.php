<?php


function connect(){
require 'config.inc.php';
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

function getToilettes($x,$y,$rangx,$rangy){
	$db=connect();
	
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
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	} catch (PDOException $e) {
	    echo $e->getMessage();
		return null;
	}

	disconnect($db);
}

function getImages($id){
	$db=connect();

	$sql = "SELECT image FROM images  WHERE id = :id ";
	
	try {
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':id', $id);
	
		$stmt->execute();
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	} catch (PDOException $e) {
	    echo $e->getMessage();
		return null;
	}

	disconnect($db);
}

?>