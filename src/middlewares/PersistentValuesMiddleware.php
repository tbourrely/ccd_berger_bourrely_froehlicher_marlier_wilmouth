<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 26/01/2017
 * Time: 15:46
 */

namespace charly\middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Middleware de valeurs persistantes dans les formulaires
 * Class PersistentValuesMiddleware
 * @package coolracing\middlewares
 */
class PersistentValuesMiddleware
{

    private $twig;

    /**
     * PersistentValuesMiddleware constructor.
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
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->twig->addGlobal('persistValues', isset($_SESSION['persistValues']) ? $_SESSION['persistValues'] : []);
        if (isset($_SESSION['persistValues'])) {
            unset($_SESSION['persistValues']);
        }
        $response = $next($request, $response);
        if ($response->getStatusCode() === 400) {
            $_SESSION['persistValues'] = $request->getParams();
        }
        return $response;
    }

}