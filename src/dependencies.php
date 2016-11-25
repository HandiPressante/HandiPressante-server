<?php
require_once 'Auth/User.php';

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new Slim\Views\Twig($settings['template_path'], $settings['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    //$user = new \Auth\User();
    //$view->getEnvironment()->addGlobal('is_user_authenticated', $user->isAuthenticated());

    $isUserAuthenticated = new Twig_SimpleFunction('isUserAuthenticated', function () {
        $user = new \Auth\User();
        return $user->isAuthenticated();
    });
    $view->getEnvironment()->addFunction($isUserAuthenticated);
    
    return $view;
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
