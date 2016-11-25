<?php

// Routes

$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(302)->withHeader('location', 'http://www.handipressante.fr');
});

require 'controllers/toilets-api.php';
require 'controllers/memo-api.php';
require 'controllers/admin.php';
