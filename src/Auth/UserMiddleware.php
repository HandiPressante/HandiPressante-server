<?php

/*
 * Inspired by tuupola's work on slim-basic-auth
 * https://github.com/tuupola/slim-basic-auth
 */

//require_once 'User.php';

namespace Auth;

use Slim\Middleware\HttpBasicAuthentication\AuthenticatorInterface;
use Slim\Middleware\HttpBasicAuthentication\ArrayAuthenticator;
use Slim\Middleware\HttpBasicAuthentication\RequestMethodRule;
use Slim\Middleware\HttpBasicAuthentication\RequestPathRule;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class UserMiddleware
{
    private $rules;
    private $options = array(
        "path" => null,
        "passthrough" => null,
        "login" => "/login"
    );

    public function __construct($options = array())
    {
        /* Setup stack for rules */
        $this->rules = new \SplStack;

        /* Store passed in options overwriting any defaults */
        $this->hydrate($options);

        /* If nothing was passed in options add default rules. */
        if (!isset($options["rules"])) {
            $this->addRule(new RequestMethodRule(array(
                "passthrough" => array("OPTIONS")
            )));
        }

        /* If path was given in easy mode add rule for it. */
        if (null !== $this->options["path"]) {
            $this->addRule(new RequestPathRule([
                "path" => $this->options["path"],
                "passthrough" => $this->options["passthrough"]
            ]));
        }
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $host = $request->getUri()->getHost();
        $scheme = $request->getUri()->getScheme();
        $server_params = $request->getServerParams();

        /* If rules say we should not authenticate call next and return. */
        if (false === $this->shouldAuthenticate($request)) {
            return $next($request, $response);
        }

        $user = new User();
        if (!$user->isAuthenticated()) {
            return $response->withRedirect($this->options["login"]);
        }

        /* Everything ok, call next middleware. */
        return $next($request, $response);
    }

    private function hydrate($data = array())
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            }
        }
    }

    private function shouldAuthenticate(RequestInterface $request)
    {
        /* If any of the rules in stack return false will not authenticate */
        foreach ($this->rules as $callable) {
            if (false === $callable($request)) {
                return false;
            }
        }
        return true;
    }

    public function getPath()
    {
        return $this->options["path"];
    }

    /* Do not mess with path right now */
    private function setPath($path)
    {
        $this->options["path"] = $path;
        return $this;
    }

    public function getPassthrough()
    {
        return $this->options["passthrough"];
    }

    private function setPassthrough($passthrough)
    {
        $this->options["passthrough"] = $passthrough;
        return $this;
    }

    public function getLogin()
    {
        return $this->options["login"];
    }

    private function setLogin($login)
    {
        $this->options["login"] = $login;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules(array $rules)
    {
        /* Clear the stack */
        unset($this->rules);
        $this->rules = new \SplStack;

        /* Add the rules */
        foreach ($rules as $callable) {
            $this->addRule($callable);
        }
        return $this;
    }

    public function addRule($callable)
    {
        $this->rules->push($callable);
        return $this;
    }
}
