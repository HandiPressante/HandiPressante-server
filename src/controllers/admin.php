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

$app->get('/admin/manage-access', function ($request, $response, $args) {
    $stmt = $this->pdo->prepare('SELECT id, email FROM admins ORDER BY id DESC');
    $stmt->execute();
    $admins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // CSRF Token
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $token = [
        'nameKey' => $nameKey,
        'valueKey' => $valueKey,
        'name' => $request->getAttribute($nameKey),
        'value' => $request->getAttribute($valueKey)
    ];

    return $this->renderer->render($response, 'manage-access.html.twig', array('admins' => $admins, 'token' => $token));
})->add($app->getContainer()->get('csrf'));

$app->get('/admin/add-access', function ($request, $response, $args) {
    // CSRF Token
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $token = [
        'nameKey' => $nameKey,
        'valueKey' => $valueKey,
        'name' => $request->getAttribute($nameKey),
        'value' => $request->getAttribute($valueKey)
    ];

    return $this->renderer->render($response, 'add-access.html.twig', array('token' => $token));
})->add($app->getContainer()->get('csrf'));

$app->post('/admin/add-access', function ($request, $response) {
    $data = $request->getParsedBody();
    $signup = [];
    $signup['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $signup['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $signup['password-confirm'] = filter_var($data['password-confirm'], FILTER_SANITIZE_STRING);

    $error = '';
    if (filter_var($signup['email'], FILTER_VALIDATE_EMAIL)) {
        if ($signup['password'] == $signup['password-confirm']) {
            if (strlen($signup['password']) >= 8) {
                $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email');
                $stmt->bindParam(':email', $signup['email']);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    $stmt = $this->pdo->prepare('INSERT INTO admins (email, pass_hash) VALUES (:email, :pass_hash)');
                    $stmt->bindParam(':email', $signup['email']);
                    $stmt->bindParam(':pass_hash', password_hash($signup['password'], PASSWORD_BCRYPT));
                    $stmt->execute();

                    return $response->withRedirect("/admin/manage-access");
                } else {
                    $error = 'Cette adresse e-mail est déjà utilisée.';
                }
            } else {
                $error = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
        } else {
            $error = 'Les mots de passe ne sont pas identiques.';
        }
    } else {
        $error = 'Veuillez entrer une adresse e-mail valide.';
    }

    // CSRF Token
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $token = [
        'nameKey' => $nameKey,
        'valueKey' => $valueKey,
        'name' => $request->getAttribute($nameKey),
        'value' => $request->getAttribute($valueKey)
    ];

    return $this->renderer->render($response, 'add-access.html.twig', array('error' => $error, 'email' => $signup['email'], 'token' => $token));
})->add($app->getContainer()->get('csrf'));