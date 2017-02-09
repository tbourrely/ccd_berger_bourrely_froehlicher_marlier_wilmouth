<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 22/01/2017
 * Time: 16:53
 */

namespace charly\controllers;

use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseController
 * Classe parent des controllers
 * @package coolracing\controllers
 */
class BaseController
{

    private $container;

    /**
     * BaseController constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Fonction de rendu de vue
     * @param ResponseInterface $response
     * @param $view
     * @param array $params
     */
    public function render(ResponseInterface $response, $view, $params = array())
    {
        $this->container->views->render($response, $view . '.html.twig', $params);
    }

    /**
     * Fonction de redirection
     * @param ResponseInterface $response
     * @param $name
     * @param array $params
     * @param int $status
     * @return static
     */
    public function redirect(ResponseInterface $response, $name, $params = array(), $status = 302)
    {
        return $response->withStatus(302)->withHeader('Location', $this->container->get('router')->pathFor($name, (!is_null($params) ? $params : [])));
    }

    /**
     * Fonction qui genere des messages flas
     * @param $type
     * @param $message
     */
    public function flash($type, $message)
    {
        if (isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Getter d'attributs
     * @param $name
     * @return mixed
     */
    public function __get($name) {
        return $this->$name;
    }

}