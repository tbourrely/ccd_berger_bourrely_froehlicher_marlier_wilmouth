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
        $note = 0;
        if(isset($_GET['note'])){
            $note = $_GET['note'];
        }
        if (isset($args['filter1'])){
            if($args['filter1'] === "places"){
                if (isset($_SESSION['user'])){
                    $nbUsers = Groupe::where('proprietaire', '=', $_SESSION['user']['id'])->first()->nbUsers;
                    if($nbUsers){
                        $tab["logements"] = Logement::where('places', '=', $nbUsers)->where('moy', '>=', $note)->get();
                        $this->render($resp,'logement/listLogement',$tab);
                    }else{
                        echo "error nbusers";
                    }
                }else{
                    echo "non connecte";
                }
            }else{
                $tab["logements"]=Logement::where('places','>',0)->where('moy', '>=', $note)->get();
                $this->render($resp,'logement/listLogement',$tab);
            }

        }else{
            $tab["logements"]=Logement::where('places','>',0)->where('moy', '>=', $note)->get();
            $this->render($resp,'logement/listLogement',$tab);
        }


    }

    public function detailsLogement(RequestInterface $req, ResponseInterface $resp, $args){
        $tab['logement'] = \charly\models\Logement::where('id', $args['id'])->first();
        $this->render($resp, 'logement/detailsLogement',$tab);
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

                    $g->moy=round($g->notations()->avg('note'),1);
                    $g->save();

                    return $this->redirect($resp, 'detailsLogement',['id' => $g->id]);
                }
            }
        }else {
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($resp, 'utilisateur.connexion.form');
        }
    }
}