<?php
require_once 'config.inc.php';

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
?>