<?php
require_once __DIR__ . '/../Auth/User.php';

$app->get('/admin/login', function ($request, $response, $args) {
    $user = new \Auth\User();
    if ($user->isAuthenticated()) {
        return $response->withRedirect("/admin");
    }

    return $this->renderer->render($response, 'login.html.twig', $args);
});

$app->post('/admin/login', function ($request, $response) {
    $user = new \Auth\User();
    if ($user->isAuthenticated()) {
        return $response->withRedirect("/admin");
    }

	$data = $request->getParsedBody();
    $login_data = [];
    $login_data['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $login_data['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);

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
    $email = filter_var($data['email'], FILTER_SANITIZE_STRING);

    $error = '';
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $stmt = $this->pdo->prepare('INSERT INTO admins (email) VALUES (:email)');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $id = $this->pdo->lastInsertId();
            $passwordToken = requirePasswordReset($this->pdo, $id);

            $accountCreationEmail = $this->renderer->fetch('emails/account-creation.txt.twig', ['id' => $id, 'passwordToken' => $passwordToken]);

            $message = Swift_Message::newInstance('Création de vos identifiants d\'administration')
                ->setFrom(array('no-reply@handipressante.fr' => 'HandiPressante'))
                ->setTo(array($email))
                ->setBody($accountCreationEmail);

            if ($this->mailer->send($message)) {
                return $response->withRedirect("/admin/manage-access");
            } else {
                $error = 'Une erreur a eu lieu lors de l\'envoi du mail de création du compte, veuillez réessayer ultérieurement.';
            }

        } else {
            $error = 'Cette adresse e-mail est déjà utilisée.';
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

    return $this->renderer->render($response, 'add-access.html.twig', array('error' => $error, 'email' => $email, 'token' => $token));
})->add($app->getContainer()->get('csrf'));


/*
 * Re-setting password feature
 */

function requirePasswordReset($pdo, $id) {
    $token = bin2hex(random_bytes(32));
    $tokenTimestamp = time();

    $stmt = $pdo->prepare('UPDATE admins SET token = :token, token_timestamp = :token_timestamp WHERE id = :id');
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":token_timestamp", $tokenTimestamp);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    return $token;
}

function isPasswordTokenValid($pdo, $id, $token, &$error) {
    $stmt = $pdo->prepare('SELECT token, token_timestamp FROM admins WHERE id = :id LIMIT 1');
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $error = '';
    if ($admin = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $maxValidity = 3600 * 48; // 48 hours
        $elapsedTime = time() - $admin['token_timestamp'];

        if (strlen($admin['token']) > 0 && $admin['token'] == $token && $elapsedTime <= $maxValidity) {
            return true;
        } else {
            $error = 'Désolé, le jeton d\'initialisation du mot de passe n\'est plus valide. <br /><a href="/admin/require-password-reset">Cliquez ici</a> pour recevoir un nouveau jeton par mail.';
        }
    } else {
        $error = 'Ce compte n\'existe pas.';
    }

    return false;
}

$app->get('/admin/set-password/{id}-{password_token}', function ($request, $response, $args) {
    $id = (int)$args['id'];
    $passwordToken = filter_var($args['password_token'], FILTER_SANITIZE_STRING);

    $error = '';
    $printForm = isPasswordTokenValid($this->pdo, $id, $passwordToken, $error);

    return $this->renderer->render($response, 'set-password.html.twig', array('id' => $id, 'password_token' => $passwordToken, 'error' => $error, 'printForm' => $printForm));
});

$app->post('/admin/set-password', function ($request, $response) {
    $data = $request->getParsedBody();
    
    $id = (int)$data['id'];
    $token = filter_var($data['password_token'], FILTER_SANITIZE_STRING);
    
    $error = '';
    $printForm = false;

    if (isPasswordTokenValid($this->pdo, $id, $token, $error)) {
        print('Token Valid');
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
        $passwordConfirm = filter_var($data['password-confirm'], FILTER_SANITIZE_STRING);

        if ($password == $passwordConfirm) {
            print('PassOK1');
            if (strlen($password) >= 8) {
                print('PassOK2');
                $stmt = $this->pdo->prepare('UPDATE admins SET pass_hash = :pass_hash, token = NULL, token_timestamp = NULL WHERE id = :id');
                $stmt->bindParam(':pass_hash', password_hash($password, PASSWORD_BCRYPT));
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                print('PassHash updated');

                return $response->withRedirect("/admin/login");
            } else {
                $error = 'Le mot de passe doit contenir au moins 8 caractères.';
                $printForm = true;
            }
        } else {
            $error = 'Les mots de passe ne sont pas identiques.';
            $printForm = true;
        }
    }

    return $this->renderer->render($response, 'set-password.html.twig', array('id' => $id, 'error' => $error, 'printForm' => $printForm));
});

$app->get('/admin/require-password-reset', function ($request, $response, $args) {
    return $this->renderer->render($response, 'require-password-reset.html.twig');
});

$app->post('/admin/require-password-reset', function ($request, $response) {
    $data = $request->getParsedBody();
    $email = filter_var($data['email'], FILTER_SANITIZE_STRING);

    $error = '';
    $sent = false;
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If there is no account bound to the given email, we don't inform the user, to prevent targeting.
        $sent = true;

        $stmt = $this->pdo->prepare('SELECT id FROM admins WHERE email = :email LIMIT 1');
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($admin = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $token = requirePasswordReset($this->pdo, $admin['id']);

            $passwordResetEmail = $this->renderer->fetch('emails/password-reset.txt.twig', ['id' => $admin['id'], 'token' => $token]);

            $message = Swift_Message::newInstance('Réinitialisation du mot de passe')
                ->setFrom(array('no-reply@handipressante.fr' => 'HandiPressante'))
                ->setTo(array($email))
                ->setBody($passwordResetEmail);

            if (!$this->mailer->send($message)) {
                $sent = false;
                $error = 'Une erreur a eu lieu lors de l\'envoi du mail de réinitialisation, veuillez réessayer ultérieurement.';
            }
        }
    } else {
        $error = 'L\'adresse que vous avez entré n\'est pas une adresse e-mail valide.';
    }

    return $this->renderer->render($response, 'require-password-reset.html.twig', array('sent' => $sent, 'error' => $error));
});
