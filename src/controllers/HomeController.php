<?php
/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 09/02/2017
 * Time: 21:00
 */

namespace charly\controllers;


use charly\models\Logement;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends BaseController
{
    public function index(RequestInterface $request, ResponseInterface $response, $args)
    {
        $tab['logements'] = Logement::orderBy('moy','DESC')->take(6)->get();

        $this->render($response,'home',$tab);
    }
}