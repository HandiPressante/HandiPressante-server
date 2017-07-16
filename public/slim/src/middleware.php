<?php

// Application middleware

$app->add(new \RKA\SessionMiddleware([
	'name' => 'UserSession'
]));

$app->add(new \App\Auth\UserMiddleware([
	"path" => "/admin",
	"passthrough" => ["/admin/login", "/admin/require-password-reset", "/admin/set-password"],
	"login" => "/admin/login"
]));
