<?php
require_once '../config.inc.php';
require_once '../controller/toilettes.php';
require_once '../controller/memos.php';

require_once $config['autoload'];

$app = new \Slim\App();


/*
$app->get('/toilettes/fiches/{id}', function ($request, $response, $args) {
	//  http://localhost/handipressante-server/web/api.php/toilettes/fiches/1
	$wc = getFiches($args['id']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});
*/

// Pour générer des salts
$app->get('/memos-salt', function ($request, $response, $args) {
	$res = json_encode(generateSalt(16));

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Pour les mémos
$app->get('/memos-list', function ($request, $response, $args) {
	$memos = getMemos();
	$res = json_encode($memos);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Get one toilet
$app->get('/toilet/{id}', function ($request, $response, $args) {
	$toilet = null;
	$res = getToilet($args['id']);

	if ($res['success']) {
		$toilet = $res['result'];
	}

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write(json_encode(array($toilet)));
});

// Add toilet
$app->post('/toilet-add', function ($request, $response, $args) {
	$uuid = $request->getParsedBody()['uuid'];
	$toilet_name = $request->getParsedBody()['toilet_name'];
	$toilet_accessible = $request->getParsedBody()['toilet_accessible'];
	$toilet_description = $request->getParsedBody()['toilet_description'];
	$toilet_latitude = $request->getParsedBody()['toilet_latitude'];
	$toilet_longitude = $request->getParsedBody()['toilet_longitude'];

	$res = addToilet(
		$toilet_name,
		$toilet_accessible,
		$toilet_description,
		$toilet_latitude,
		$toilet_longitude);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Edit toilet
$app->post('/toilet-edit', function ($request, $response, $args) {
	$uuid = $request->getParsedBody()['uuid'];
	$toilet_id = $request->getParsedBody()['toilet_id'];
	$toilet_name = $request->getParsedBody()['toilet_name'];
	$toilet_accessible = $request->getParsedBody()['toilet_accessible'];
	$toilet_description = $request->getParsedBody()['toilet_description'];
	$toilet_latitude = $request->getParsedBody()['toilet_latitude'];
	$toilet_longitude = $request->getParsedBody()['toilet_longitude'];

	$res = editToilet(
		$toilet_id,
		$toilet_name,
		$toilet_accessible,
		$toilet_description,
		$toilet_latitude,
		$toilet_longitude);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Rate toilet
$app->post('/toilet-rate', function ($request, $response, $args) {
	$uuid = $request->getParsedBody()['uuid'];
	$toilet_id = $request->getParsedBody()['toilet_id'];
	$toilet_cleanliness = $request->getParsedBody()['toilet_cleanliness'];
	$toilet_facilities = $request->getParsedBody()['toilet_facilities'];
	$toilet_accessibility = $request->getParsedBody()['toilet_accessibility'];

	$res = rateToilet(
		$toilet_id,
		$uuid,
		$toilet_cleanliness,
		$toilet_facilities,
		$toilet_accessibility);

	if ($res['success']) 
	{
		$res = getToilet($toilet_id);
		
		if ($res['success']) 
		{
			$toilet = $res['result'];
			$res = array('success' => true, 
				'toilet_cleanliness' => $toilet['moyenne_proprete'], 
				'toilet_facilities' => $toilet['moyenne_equipement'], 
				'toilet_accessibility' => $toilet['moyenne_accessibilite']);
		}
	}

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write(json_encode($res));
});

// Send photo
$app->post('/toilet-add-photo', function ($request, $response, $args) {
	$uuid = $request->getParsedBody()['uuid'];
	$toilet_id = $request->getParsedBody()['toilet_id'];
	$files = $request->getUploadedFiles();

	$result = array();

	$photo = '';
	if (isset($files['photo']))
		$photo = $files['photo'];

    $result['error'] = savePhoto($uuid, $toilet_id, $photo);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write(json_encode($result));
});

// Get photo list for a given toilet sheet
$app->get('/toilet-photo-list/{toilet_id}', function ($request, $response, $args) {
	$toilet_id = (int) $args['toilet_id'];

	$photos = getPhotos($toilet_id);
	$res = json_encode($photos);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Pour la Liste
$app->get('/toilettesliste/{long}/{lat}/{min}/{max}/{max_distance}', function ($request, $response, $args) {
	//	http://localhost/handipressante-server/web/api.php/toilettesliste/-1.68047298/48.11004102/5/5/5000
	$wc =getPinsListe($args['long'], $args['lat'], $args['min'], $args['max'], $args['max_distance']);
	$res = json_encode($wc);

	$response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
	return $response->write($res);
});

// Pour la carte
$app->get('/toilettescarte/{toplong}/{toplat}/{botlong}/{botlat}', function ($request, $response, $args) {
//   http://localhost/handipressante-server/web/api.php/toilettescarte/-10/60/10/40
	$wc =getPinsCarte($args['toplong'], $args['toplat'], $args['botlong'], $args['botlat']);
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

/*

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
*/



$app->run();

?>