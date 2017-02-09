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
use charly\models\Logement;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;
use charly\models\Groupe;
use charly\models\Invitation;


class GroupeController extends BaseController
{
    public function interfaceCreationGroupe(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $this->render($response, 'group\create');
        } else {
            return $this->redirect($response, 'utilisateur.connexion.form');
        }

    }

    public function postCreerGroupe(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $errors = [];
            if (!Validator::stringType()->validate($request->getParam('description'))) {
                $errors['description'] = "La description n'est pas valide.";

            }
            $groupe = Groupe::where('proprietaire', '=', $_SESSION['user']['id'])->first();
            if (isset($groupe)) {
                $this->flash('info', "Vous avez déjà créer un groupe.");
                return $this->redirect($response, 'viewGroup', $args, 400);
            }
            if (empty($errors)) {

                $g = new Groupe();
                $g->proprietaire = $_SESSION['user']['id'];
                $g->description = $request->getParam('description');
                $g->nbUsers = 1;
                $g->status = 'ouvert';
                $g->nbinvitationok = 0;
                $g->save();

                return $this->redirect($response, 'viewGroup');

            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'utilisateur.connexion.form', $args, 400);
            }
        } else {
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function interfaceViewGroupe(RequestInterface $request, ResponseInterface $response, $args)
    {
        $errors = [];

        if (isset($_SESSION['user'])) {
            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->with('proprio', 'logementG')->first();

            if (!is_null($g)) {
                $tab['groupe'] = $g;
                $tab['invitation'] = Invitation::where('idGroupe', $g->id)->with('user')->get();

                $this->render($response, 'group\view', $tab);

            } else {
                $this->flash('info', 'Vous devez avoir créé un groupe');
                return $this->redirect($response, 'createGroup', $args, 400);
            }

        } else {
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function postAjoutLogement(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $errors = [];
            if (!Validator::intType()->validate($request->getParam('logement'))) {
                $errors['description'] = "La description n'est pas valide.";
            }
            $l = Logement::where('id', $request->getParam('logement'))->first();
            if (!is_null($l)) {

            }
            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->first();
            if (!is_null($g)) {

                if($g->status != 'ouvert'){
                    $this->flash('error', 'Vous ne pouvez plus ajouter de Logement');
                    return $this->redirect($response, 'viewGroup', $args, 400);
                }
                $g->idLogement = $l->id;
                $g->save();
                return $this->redirect($response, 'viewGroup');
            }
        } else {
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function postSupLogement(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->first();
            if (!is_null($g)) {

                if($g->status != 'ouvert'){
                    $this->flash('error', 'Vous ne pouvez plus supprimer de Logement');
                    return $this->redirect($response, 'viewGroup', $args, 400);
                }

                $g->idLogement = null;
                $g->save();
                return $this->redirect($response, 'viewGroup');
            }
        } else {
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

    public function add(RequestInterface $request, ResponseInterface $response, $args){
       if(isset($_SESSION['user'])){
           if(isset($args['id'])){
               $user = User::where('id', '=', $args['id'])->first();
               if($user){
                   $group = Groupe::where('proprietaire', '=', $_SESSION['user']['id'])->first();
                   if($group->status != 'ouvert'){
                       $this->flash('error', 'Vous ne pouvez plus ajouter d\'utilisateur');
                       return $this->redirect($response, 'viewGroup', $args, 400);
                   }
                   if($group){
                       $group->nbUsers++;
                       $group->save();
                       Invitation::updateOrCreate([
                           'idUser' => $user->id,
                           'idGroupe' => $group->id
                       ],[
                           'status' => 0,
                           'url' => 0
                       ]);
                       echo "inserted";
                   }else{
                       echo "error group";
                   }
               }else{
                   echo "error user";
               }
           }
       }
    }



    public function supprimerUser(RequestInterface $request, ResponseInterface $response, $args){

        if(isset($_SESSION['user']['id'])){

            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->first();
            $i = Invitation::where('idUser', $request->getParam('suppress'))->where('idGroupe', $g->id)->first();

            if(!is_null($i)){

                if($g->status != 'ouvert'){
                    $this->flash('error', 'Vous ne pouvez plus supprimer d\'utilisateur');
                    return $this->redirect($response, 'viewGroup', $args, 400);
                }
                $i->delete();
                $g->nbUsers-=1;
                $g->save();


            }

            return $this->redirect($response, 'viewGroup');




        }
    }
}