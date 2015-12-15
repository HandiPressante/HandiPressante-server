<?php
require_once '../../config.inc.php';
//require_once '../../controller/admin.php';

require_once $config['autoload'];


// Create container
$container = new \Slim\Container;

// Register component on container
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('../../views', [
        'cache' => '../../cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

// Create app
$app = new \Slim\App($container);


$app->get('/{id}', function ($request, $response, $args) {
    return $this->view->render($response, 'test.html.twig', [
        'id' => $args['id']
    ]);
});

$app->run();

?>