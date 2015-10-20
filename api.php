<?php

require 'C:\xampp\vendor\autoload.php';
include 'controller.php';

$app = new \Slim\Slim();
//http://localhost/Handipressante/api.php/api/351861.03/6789173.05/10000000/10000000


$app->get('/api/:x/:y/:xrang/:yrang', function($x,$y,$xrang,$yrang) use ($app){
	

	$wc=getToilettes($x,$y,$xrang,$yrang);
	$res=json_encode($wc);
	

	$app->response->setStatus(200);
	$app->response->setBody($res);
	$app->response->headers->set('Content-Type', 'application/json');
});

$app->run();

?>
