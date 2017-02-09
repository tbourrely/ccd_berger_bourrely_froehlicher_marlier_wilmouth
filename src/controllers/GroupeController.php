<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 09/02/2017
 * Time: 10:32
 */

namespace charly\controllers;

use charly\models\ContenuGroupe;
use charly\models\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;
use charly\models\Groupe;


class GroupeController extends BaseController
{
    public function interfaceCreationGroupe(RequestInterface $request, ResponseInterface $response, $args){
        if(isset($_SESSION['user'])){
            $this->render($response, 'group\create');
        }else{
            return $this->redirect($response, 'utilisateur.connexion.form');
        }

    }

    public function postCreerGroupe(RequestInterface $request, ResponseInterface $response, $args){
        if(isset($_SESSION['user'])){
            $errors = [];
            if (!Validator::stringType()->validate($request->getParam('description'))) {
                $errors['description'] = "La description n'est pas valide.";

            }
            $groupe=Groupe::where('proprietaire','=',$_SESSION['user'])->first();
            if(isset($groupe)) {
                $errors['groupedejacreer']="Vous avez déjà créer un groupe.";
            }
            if (empty($errors)) {

                $g = new Groupe();
                $g->proprietaire = $_SESSION['user'];
                $g->description = $request->getParam('description');
                $g->nbUsers = 1;
                $g->ouvert = 0;
                $g->nbinvitationok = 0;
                $g->save();

                return $this->redirect($response, 'viewGroup', ['id' => $g->id]);

            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'utilisateur.connexion.form', $args, 400);
            }
        }else{
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function interfaceViewGroupe(RequestInterface $request, ResponseInterface $response, $args){
        $errors = [];

        if(isset($_SESSION['user'])){
            $g = Groupe::where('proprietaire', $_SESSION['user'])->first();

            if(!is_null($g)){
                $this->render($response, 'group\view');

            }else{
                $this->flash('info', 'Vous devez avoir créé un groupe');
                return $this->redirect($response, 'createGroup', $args, 400);
            }

        }else{
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function add(RequestInterface $request, ResponseInterface $response, $args){
       if(isset($_SESSION['user'])){
           if(isset($args['id'])){
               $user = User::where('id', '=', $args['id'])->first();
               if($user){

                   $group = Groupe::where('proprietaire', '=', $_SESSION['user'])->first();
                   if($group){
                       
                   }
               }
           }
       }
    }


}