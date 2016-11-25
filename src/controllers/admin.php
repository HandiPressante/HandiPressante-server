<?php
require_once __DIR__ . '/../Auth/User.php';

$app->get('/admin/login', function ($request, $response, $args) {
    return $this->renderer->render($response, 'login.html.twig', $args);
});

$app->post('/admin/login', function ($request, $response) {
	$data = $request->getParsedBody();
    $login_data = [];
    $login_data['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $login_data['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);

    $user = new \Auth\User();
    $success = $user->authenticate($login_data['email'], $login_data['password'], $this->pdo);

    if ($success) {
        return $response->withRedirect("/admin");
    }

    return $this->renderer->render($response, 'login.html.twig', array('error' => true));
});

$app->get('/admin/logout', function ($request, $response, $args) {
    $user = new \Auth\User();
    $user->logout();

    return $response->withRedirect("/admin/login");
});

$app->get('/admin', function ($request, $response, $args) {
    $user = new \Auth\User();

    return $this->renderer->render($response, 'admin.html.twig', array('user' => $user));
});