<?php
require_once 'Auth/UserMiddleware.php';

// Application middleware

$app->add(new \RKA\SessionMiddleware([
	'name' => 'UserSession'
]));

$app->add(new \Auth\UserMiddleware([
	"path" => "/admin",
	"passthrough" => "/admin/login",
	"login" => "/admin/login"
]));
