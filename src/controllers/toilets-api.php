<?php

$app->get('/toilets/get-{id}', function ($request, $response, $args) {
	$toilet = [ 'id' => (int)$args['id'], 'name' => 'test' ];
	return $this->json->render($response, $toilet);
});
