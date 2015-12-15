<?php
require_once '../config.inc.php';
require_once '../controller/toilettes.php';

require_once $config['autoload'];

$app = new \Slim\App();


$app->get('/toilettes/fiches/{id}', function ($request, $response, $args) {
	$wc = getFiches($args['id']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

$app->get('/toilettes/{x}/{y}/{xrang}/{yrang}', function ($request, $response, $args) {
	$wc = getToilettes($args['x'], $args['y'], $args['xrang'], $args['yrang']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

$app->get('/toilettes/images/{id}', function ($request, $response, $args) {
	$wc = getImages($args['id']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});


$app->run();

?>