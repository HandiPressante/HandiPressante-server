<?php
require_once '../database.php';

function getMemos($last_update)
{
	$db = connect();
	$result = null;

	try {
		$stmt = $db->prepare('SELECT id, title, filename FROM memos WHERE last_update > :last_update');
		$stmt->bindParam(':last_update', $last_update);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
	    echo $e->getMessage();
	}

	disconnect($db);
	return $result;
}

/*
function getMemoName($id) {
	$db=connect();
    $succes=true;
	$sql = "SELECT name FROM memos WHERE id = :id";
	
	try {
		$stmt = $db->prepare($sql);
	
		$stmt->bindParam(':id', $id);

		$stmt->execute();
	


		$tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$res=$tmp[0]['name'];

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

function getMemos() {
	$db=connect();
    $succes=true;
	$sql = "SELECT * FROM memos";
	
	try {
		$stmt = $db->prepare($sql);
	
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
*/

?>