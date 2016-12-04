<?php
namespace App\Controller;

use Interop\Container\ContainerInterface;

class Controller {
	protected $ci;

	public function __construct(ContainerInterface $ci) {
		$this->ci = $ci;
	}

	protected function pathFor($name) {
		return $this->ci->router->pathFor($name);
	}

	protected function getRepository($name) {
		$class = 'App\\Library\\' . $name . 'Repository';
		return new $class($this->ci->pdo);
	}

	protected function getCsrfToken($request)	{
		$nameKey = $this->ci->csrf->getTokenNameKey();
		$valueKey = $this->ci->csrf->getTokenValueKey();
		
		return [
			'nameKey' => $nameKey,
			'valueKey' => $valueKey,
			'name' => $request->getAttribute($nameKey),
			'value' => $request->getAttribute($valueKey)
		];
	}
};
