<?php
namespace App\Controller;

class ToiletController extends Controller {

	public function get($request, $response, $args) {
		$toilet = [ 'id' => (int)$args['id'], 'name' => 'test' ];
		return $this->ci->json->render($response, $toilet);
	}

};
