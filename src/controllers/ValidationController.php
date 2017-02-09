<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/02/2017
 * Time: 15:01
 */

namespace charly\controllers;


use charly\models\Invitation;
use charly\models\Logement;
use Slim\Interfaces\InvocationStrategyInterface;
use charly\models\Groupe;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ValidationController extends BaseController
{
    public function validerGroupe(RequestInterface $request, ResponseInterface $response, $args){
        if(isset($_SESSION['user']['id'])) {
            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->where('id', $request->getParam('validate'))->first();
            if(!is_null($g)){
                $l=Logement::where('id', $g->idLogement)->first();
                if(!is_null($l)){
                    if($l->places==$g->nbUsers){
                        $g->status='complet';
                        $g->save();
                        $this->flash('info', 'Le groupe a bien été accepté');
                        return $this->redirect($response, 'viewGroup', $args);
                    }else{

                        $this->flash('info', 'Le nombre de places du logement ne corresponds pas au nombre de membre du groupe.');
                        return $this->redirect($response, 'viewGroup', $args);
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

    public function genererURL(RequestInterface $request, ResponseInterface $response, $args){
        $token = uniqid();
        $invitation = Invitation::where('id', $args['id'])->first();
        if(!is_null($invitation)) {
            $invitation->url = $token;
            $invitation->save();
            $this->flash('info', 'Url géneré');
            return $this->redirect($response, 'viewGroup', $args);
        }else{
            $this->flash('info', 'Aucune invitation ne correspond');
            return $this->redirect($response, 'viewGroup', $args, 400);
        }
    }

    public function validerGroupeComplet(RequestInterface $request, ResponseInterface $response, $args){
        if(isset($_SESSION['user']['id]'])) {
            $g = Groupe::where('proprietaire', $_SESSION['user']['id'])->where('id', $args['id'])->first();
            if (!is_null($g)) {
                if($g->status=='complet'){
                    $invitations=Invitation::where('idGroupe',$g->id);
                    try{
                        foreach ($invitations as $i){
                            if($i->status!='accepte'){
                                throw new \Exception("un invite n'a pas accepte l'invitation");
                            }
                        }
                        $g->status='completValide';
                        $g->save();
                        $this->flash('info', 'Le groupe a bien été validé un gérant va étudier votre demande.');
                        return $this->redirect($response, 'viewGroup', $args);
                    }catch (\Exception $e){
                        $this->flash('info', 'Un membre n\'a pas accepté l\'invitation.');
                        return $this->redirect($response, 'viewGroup', $args);
                    }

                }else{
                    $this->flash('info', 'Le groupe n\'a pas été validé une première fois.');
                    return $this->redirect($response, 'viewGroup', $args);
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

    public function accepterInvitation(RequestInterface $request, ResponseInterface $response, $args){
        $invitation = Invitation::where('url', $args['id'])->first();
        if(!is_null($invitation)) {
            if($invitation->status!='accepte') {
                $invitation->status = "accepte";
                $invitation->save();
                $this->flash('info', 'Vous avez accepter l\'invitation.');
                return $this->redirect($response, 'index');
            }else{
                $this->flash('info', 'Vous avez déjà répondu à l\'invitation.');
                return $this->redirect($response, 'index');
            }
        }else{
            $this->flash('info', 'Invitation invalide');
            return $this->redirect($response, 'index');
        }
    }

    public function refuserInvitation(RequestInterface $request, ResponseInterface $response, $args){
        $invitation = Invitation::where('url', $args['id'])->first();
        $g=Groupe::where('id',$invitation->idGroupe);
        if(!is_null($g)) {
            if (!is_null($invitation)) {
                if ($invitation->status != 'accepte') {
                    $g->status="ouvert";
                    $g->nbUsers=$g->nbUsers-1;
                    $invitation->delete();
                    $g->save();
                    $invs=Invitation::where('idGroupe',$g->id)->get();
                    foreach ($invs as $i){
                        $i->status=0;
                        $i->url=0;
                        $i->save();
                    }
                    $this->flash('info', 'Vous avez refuser l\'invitation.');
                    return $this->redirect($response, 'index');
                } else {
                    $this->flash('info', 'Vous avez déjà répondu à l\'invitation.');
                    return $this->redirect($response, 'index');
                }
            } else {
                $this->flash('info', 'Invitation invalide');
                return $this->redirect($response, 'index');
            }
        }else {
            $this->flash('info', 'Groupe inexistant');
            return $this->redirect($response, 'index');
        }
    }

    public function rejoindreGroupe(RequestInterface $request, ResponseInterface $response, $args){
        $i = Invitation::where('url', $args['url'])->first();
        $g = Groupe::where('id', $i->idGroupe)->with('proprio', 'logementG')->first();

        $tab['groupe'] = $g;
        $tab['vraiInvitation'] = $i;
        $tab['invitation'] = Invitation::where('idGroupe', $g->id)->with('user')->get();
        $this->render($response, 'group/join', $tab);
    }
}




