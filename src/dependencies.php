<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new Slim\Views\Twig($settings['template_path'], $settings['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    //$user = new \Auth\User();
    //$view->getEnvironment()->addGlobal('is_user_authenticated', $user->isAuthenticated());

    $isUserAuthenticated = new Twig_SimpleFunction('isUserAuthenticated', function () {
        $user = new \App\Auth\User();
        return $user->isAuthenticated();
    });
    $view->getEnvironment()->addFunction($isUserAuthenticated);

    $getFlashMessages = new Twig_SimpleFunction('flash', function ($tag = null) {
        $flash = new Slim\Flash\Messages();
        if (null !== $tag) {
            return $flash->getMessage($tag);
        }

        return $flash->getMessages();
    });
    $view->getEnvironment()->addFunction($getFlashMessages);
    
    return $view;
};

// flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages();
};

// json renderer
$container['json'] = function ($c) {
    $renderer = new class {
        public function render($response, $data) {
            $json = json_encode($data);
            $response->write($json);
            return $response->withHeader('Content-type', 'application/json;charset=utf-8');
        }
    };

    return $renderer;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// pdo
$container['pdo'] = function ($c) {
    $settings = $c->get('settings')['pdo'];

    $pdo = new PDO('mysql:host=localhost;dbname=' . $settings['dbname'].';charset=utf8', $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

// mailer
$container['mailer'] = function ($c) {
    $settings = $c->get('settings')['mailer'];

    $transport = Swift_SmtpTransport::newInstance($settings['host'], $settings['port'], $settings['encryption'])
        ->setUsername($settings['username'])
        ->setPassword($settings['password']);

    return Swift_Mailer::newInstance($transport);
};

// csrf
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

// errors
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c->get('view')->render($response, 'errors/404.html.twig')->withStatus(404);
    };
};
