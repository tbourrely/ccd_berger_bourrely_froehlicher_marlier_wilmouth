<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/02/2017
 * Time: 10:22
 */

namespace charly\controllers;


use charly\models\Groupe;
use charly\models\Logement;
use charly\models\Notation;
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
        $tab['note'] = round($tab['logement']->notations()->avg('note'),1);
        $this->render($resp, 'logement/detailsLogement',$tab);
    }

    public function listLogementFilter(RequestInterface $request, ResponseInterface $response, $args){
        if (isset($_SESSION['user'])){
            $nbUsers = Groupe::where('proprietaire', '=', $_SESSION['user']['id'])->first()->nbUsers;
            if($nbUsers){
                $tab["logements"] = Logement::where('places', '=', $nbUsers)->get();
                $tab["button"]="filter";
                $this->render($response,'logement/listLogement',$tab);
            }else{
                echo "error nbusers";
            }
        }else{
            echo "non connecte";
        }
    }


    public function rateLogement(RequestInterface $req, ResponseInterface $resp, $args){
        if(isset($_SESSION['user'])){
            if(isset($args['id']) && isset($args['note'])){
                $g = Logement::where('id',$args['id'])->first();
                if(!is_null($g)){
                    $n = new Notation();
                    $n->idLogement=$args['id'];
                    $n->note=$args['note'];
                    $n->save();
                    return $this->redirect($resp, 'detailsLogement',['id' => $g->id]);
                }
            }
        }else {
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($resp, 'utilisateur.connexion.form');
        }
    }
}