<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/02/2017
 * Time: 10:22
 */

namespace charly\controllers;


use charly\models\Logement;

class LogementController extends BaseController
{

    public function listLogement(RequestInterface $req, ResponseInterface $resp, $args){
        $logements=Logement::where('places','>',0)->get();
        if (is_null($logements)) {
            $this->flash('info', "Impossible de trouver des logements.");
            return $this->redirect($resp, 'index');
        } else {
            $this->render($resp,'layout',$logements);
        }
    }
}