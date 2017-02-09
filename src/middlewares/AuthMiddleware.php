<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 26/01/2017
 * Time: 15:08
 */

namespace charly\middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Middleware de verification de compte
 * Class AuthMiddleware
 * @package charly\middlewares
 */
class AuthMiddleware
{

    private $twig;

    /**
     * AuthMiddleware constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Fonction d'invocation du middleware
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->twig->addGlobal('auth', isset($_SESSION['user']) ? $_SESSION['user'] : []);
        return $next($request, $response);
    }

}