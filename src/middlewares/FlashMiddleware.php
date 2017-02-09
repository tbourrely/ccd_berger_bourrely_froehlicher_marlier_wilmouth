<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 26/01/2017
 * Time: 15:08
 */

namespace coolracing\middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Middleware de messages flash
 * Class FlashMiddleware
 * @package coolracing\middlewares
 */
class FlashMiddleware
{

    private $twig;

    /**
     * FlashMiddleware constructor.
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
        $this->twig->addGlobal('flash', isset($_SESSION['flash']) ? $_SESSION['flash'] : []);
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
        return $next($request, $response);
    }

}