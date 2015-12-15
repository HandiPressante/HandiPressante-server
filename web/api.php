<?php
require 'config.inc.php';
require 'controller.php';

require $config['slimpath'];

$app = new \Slim\Slim();


$app->get('/toilettes/fiches/:id', function($id) use ($app){
	

	$wc=getFiches($id);
	$res=json_encode($wc);

	$app->response->setStatus(200);
	$app->response->setBody($res);
	$app->response->headers->set('Content-Type', 'application/json');

});

$app->get('/toilettes/:x/:y/:xrang/:yrang', function($x,$y,$xrang,$yrang) use ($app){
	

	$wc=getToilettes($x,$y,$xrang,$yrang);
	$res=json_encode($wc);

	$app->response->setStatus(200);
	$app->response->setBody($res);
	$app->response->headers->set('Content-Type', 'application/json');

});

$app->get('/toilettes/images/:id', function($id) use ($app){
	

	$wc=getImages($id);
	$res=json_encode($wc);

	$app->response->setStatus(200);
	$app->response->setBody($res);
	$app->response->headers->set('Content-Type', 'application/json');

});


$app->run();









//$post = request($app);


?>