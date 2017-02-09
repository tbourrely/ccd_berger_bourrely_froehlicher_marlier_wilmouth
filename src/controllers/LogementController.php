<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/02/2017
 * Time: 10:22
 */

namespace charly\controllers;


use charly\models\Logement;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogementController extends BaseController
{

    public function listLogement(RequestInterface $req, ResponseInterface $resp, $args){
        $tab["logements"]=Logement::where('places','>',0)->get();
        $this->render($resp,'logement/listLogement',$tab);

    }

    public function detailsLogement(RequestInterface $req, ResponseInterface $resp, $args){
        $tab['logement'] = \charly\models\Logement::where('id', $args['id'])->first();
        $this->render($resp, 'logement/detailsLogement',$tab);
    }
}