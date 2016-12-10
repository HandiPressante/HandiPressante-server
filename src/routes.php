<?php
use \App\Controller\ToiletController;
use \App\Controller\MemoController;
use \App\Controller\AdminController;
use \App\Controller\AccessController;

// Routes

$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(302)->withHeader('location', 'http://www.handipressante.fr');
});


$app->group('/toilets', function () {

	$this->get('/get-{id:[0-9]+}', ToiletController::class . ':get');
	$this->get('/get-nearby/{lat}/{long}/{mincount}/{maxcount}/{maxdistance}', ToiletController::class . ':getNearby');
	$this->get('/get-area/{lat_nw}/{long_nw}/{lat_se}/{long_se}', ToiletController::class . ':getArea');

});


$app->group('/memo', function () {

	$this->get('/list', MemoController::class . ':list')
		->setName('memo_list');

});


$app->group('/admin', function () {

	$this->get('', AdminController::class . ':index')
		->setName('admin_index');

	$this->map(['GET', 'POST'], '/login', AdminController::class . ':login')
		->setName('admin_login');

	$this->get('/logout', AdminController::class . ':logout')
		->setName('admin_logout');

	$this->get('/set-password/{id:[0-9]+}-{token}', AdminController::class . ':setPassword')
		->setName('admin_setpassword');

	$this->post('/set-password', AdminController::class . ':setPasswordPost')
		->setName('admin_setpassword_post');

	$this->map(['GET', 'POST'], '/require-password-reset', AdminController::class . ':requirePasswordReset')
		->setName('admin_passwordreset');


	$this->group('/access', function() {

		$this->get('/manage', AccessController::class . ':manage')
		->setName('access_manage')
		->add($this->getContainer()->get('csrf'));

		$this->map(['GET', 'POST'], '/add', AccessController::class . ':add')
		->setName('access_add')
		->add($this->getContainer()->get('csrf'));

		$this->get('/remove-{id:[0-9]+}', AccessController::class . ':remove')
		->setName('access_remove')
		->add($this->getContainer()->get('csrf'));

		$this->post('/remove-confirm', AccessController::class . ':removeConfirm')
		->setName('access_remove_confirm')
		->add($this->getContainer()->get('csrf'));

	});


	$this->group('/memo', function() {

		$this->get('/manage', MemoController::class . ':manage')
		->setName('memo_manage')
		->add($this->getContainer()->get('csrf'));

		$this->map(['GET', 'POST'], '/add', MemoController::class . ':add')
		->setName('memo_add')
		->add($this->getContainer()->get('csrf'));

		$this->get('/remove-{id:[0-9]+}', MemoController::class . ':remove')
		->setName('memo_remove')
		->add($this->getContainer()->get('csrf'));

		$this->post('/remove-confirm', MemoController::class . ':removeConfirm')
		->setName('memo_remove_confirm')
		->add($this->getContainer()->get('csrf'));

	});

});
