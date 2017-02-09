<?php

namespace charly\controllers;

use charly\models\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UtilisateursController extends BaseController
{

    public function listUsers(RequestInterface $req, ResponseInterface $resp, $args){
        $tab['users'] = \charly\models\User::all();

        $this->render($resp, 'utilisateurs/listUtilisateurs',$tab);
    }

    public function detailsUser(RequestInterface $req, ResponseInterface $resp, $args){
        $tab['user'] = \charly\models\User::where('id', $args['id'])->first();

        $this->render($resp, 'utilisateurs/detailsUtilisateur',$tab);
    }

    public function listUsersJson(RequestInterface $request, ResponseInterface $response, $args){
        $search = $args['search'];
        $tab = \charly\models\User::where('nom', 'LIKE', "%$search%")->get();
        $users = array();
        foreach ($tab as $u){
            array_push($users, $u->nom);
        }
        echo json_encode($users);
    }
    public function retrieveId(RequestInterface $request, ResponseInterface $response, $args){
        $search = $args['name'];
        $tab = \charly\models\User::where('nom', '=', $search)->first();
        echo $tab->id;
    }

}