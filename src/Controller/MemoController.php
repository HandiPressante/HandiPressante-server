<?php
namespace App\Controller;

class MemoController extends Controller {

	public function list($request, $response, $args) {
		$memoList = [
			[ 'id' => 1, 'title' => 'Fiche 1', 'filename' => 'fiche1.pdf' ],
			[ 'id' => 2, 'title' => 'Fiche 2', 'filename' => 'fiche2.pdf' ],
			[ 'id' => 3, 'title' => 'Fiche 3', 'filename' => 'fiche3.pdf' ],
		];

		return $this->ci->json->render($response, $memoList);
	}

};
