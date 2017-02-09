<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/02/2017
 * Time: 15:01
 */

namespace charly\controllers;


use charly\models\Logement;

class ValidationController extends BaseController
{
    public function validerGroupe(RequestInterface $request, ResponseInterface $response, $args){
        if(isset($_SESSION['user'])) {
             $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->where('id', $args['id'])->first();
            if(!is_null($g)){
                $l=Logement::where('id',$g->idLogement);
                if(isset($l)){
                    if($l->places==$g->nbUsers){
                        $g->status='complet';
                        $g->save();
                        $this->flash('info', 'Le groupe a bien été accepté');
                        return $this->redirect($response, 'viewGroup', $args, 400);
                    }else{
                        $this->flash('info', 'Le nombre de places du logement ne corresponds pas au nombre de membre du groupe.');
                        return $this->redirect($response, 'viewGroup', $args, 400);
                    }

                }else{
                    $this->flash('info', 'Aucun logement associé au groupe.');
                    return $this->redirect($response, 'viewGroup', $args, 400);
                }
            }else{
                $this->flash('info', 'Vous devez avoir créé un groupe.');
                return $this->redirect($response, 'createGroup', $args, 400);
            }
        }else{
            $this->flash('info', 'Vous devez être connecté.');
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function genererURL(){

    }

}