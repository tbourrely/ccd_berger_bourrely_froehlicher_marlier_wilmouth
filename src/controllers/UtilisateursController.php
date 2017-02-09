<?php

namespace charly\controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UtilisateursController extends BaseController
{

    public function listUsers(RequestInterface $req, ResponseInterface $resp, $args){
        $tab['users'] = \charly\models\User::all();

        $this->render($resp, 'utilisateurs/listUtilisateurs',$tab);
    }

}