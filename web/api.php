<?php
require_once '../config.inc.php';
require_once '../controller/toilettes.php';

require_once $config['autoload'];

$app = new \Slim\App();


$app->get('/toilettes/fiches/{id}', function ($request, $response, $args) {
	//  http://localhost/handipressante-server/web/api.php/toilettes/fiches/1
	$wc = getFiches($args['id']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Pour la Liste
$app->get('/toilettesliste/{long}/{lat}/{min}/{max}/{max_distance}', function ($request, $response, $args) {
	//	http://localhost/handipressante-server/web/api.php/toilettesliste/-1.68047298/48.11004102/5/5/5000
	$wc =getToilettesListe($args['long'], $args['lat'], $args['min'], $args['max'], $args['max_distance']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Pour la carte
$app->get('/toilettescarte/{toplong}/{toplat}/{botlong}/{botlat}', function ($request, $response, $args) {
//   http://localhost/handipressante-server/web/api.php/toilettescarte/-10/60/10/40
	$wc =getToilettesFrame($args['toplong'], $args['toplat'], $args['botlong'], $args['botlat']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

$app->get('/toilettes/images/{id}', function ($request, $response, $args) {
//   http://localhost/handipressante-server/web/api.php/toilettes/images/80
	$wc = getImages($args['id']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});


$app->get('/memoslist', function ($request, $response, $args) {
 //  http://localhost/handipressante-server/web/api.php/memoslist
  	$wc = getMemos();
	$res = json_encode($wc);
    
	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

$app->get('/memos/{id}', function ($request, $response, $args) {
	//   http://localhost/handipressante-server/web/api.php/memos/1
global $config;
$dirpath= $config['memos'];
$file = $dirpath.getMemoName($args['id']);

$response = $response->withHeader('Content-Description', 'File Transfer');
$response = $response->withHeader('Content-Type','application/pdf');
$response = $response->withHeader('Content-Disposition:','attachment; filename="'.basename($file).'"');
$response = $response->withHeader('Expires',' 0');
$response = $response->withHeader('Cache-Control','must-revalidate');
$response = $response->withHeader('Pragma', "public");
$response = $response->withHeader('Content-Length', filesize($file));

return $response->write(readfile($file));
});



$app->run();

?>