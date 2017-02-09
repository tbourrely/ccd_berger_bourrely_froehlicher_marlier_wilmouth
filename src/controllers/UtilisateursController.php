<?php

namespace charly\controllers;

use charly\models\NotationUser;
use charly\models\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class UtilisateursController extends BaseController
{

    public function inscriptionForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (!isset($_SESSION['user'])) {
            $this->render($response, 'utilisateurs/inscription');
        } else {
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        }
    }

    public function inscription(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (!isset($_SESSION['user'])) {
            $errors = [];
            if (!Validator::stringType()->validate($request->getParam('nom'))) {
                $errors['nom'] = "Veuillez spécifier un nom.";
            }
            if (!Validator::email()->validate($request->getParam('email'))) {
                $errors['email'] = "Votre email n'est pas valide.";
            }
            if (!Validator::intVal()->validate($request->getParam('age'))) {
                $errors['age'] = "Veuillez spécifier un age.";
            }
            if (!Validator::stringType()->validate($request->getParam('password'))) {
                $errors['password'] = "Veuillez spécifier un mot de passe.";
            }
            if ($request->getParam('password_verify') != $request->getParam('password')) {
                $errors['password_verify'] = "Les mots de passe ne correspondent pas.";
            }

            $userExists = User::where('email', '=', $request->getParam('email'))->first();
            if (!is_null($userExists)) {
                $errors['email'] = "Email déjà utilisé.";
            }

            if (empty($errors)) {
                $params = $request->getParams();
                unset($params['csrf_name']);
                unset($params['csrf_value']);
                unset($params['password_verify']);
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => 10]);
                $idUser = user::create($params);
                $this->createSession('user', ['id' => $idUser->id, 'gest' => 0]);
                $this->flash('success', "Inscription réussie avec succès.");
                return $this->redirect($response, 'utilisateur.compte', ['id' => $idUser->id]);
            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'utilisateur.inscription.form', $args, 400);
            }
        } else {
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        }
    }

    public function connexionForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (!isset($_SESSION['user'])) {
            $this->render($response, 'utilisateurs/connexion');
        } else {
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        }
    }

    public function connexion(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (!isset($_SESSION['user'])) {
            $errors = [];
            if (!Validator::stringType()->validate($request->getParam('password'))) {
                $errors['password'] = "Veuillez spécifier un mot de passe.";
            }
            if (!Validator::email()->validate($request->getParam('email'))) {
                $errors['email'] = "Votre email n'est pas valide.";
            }
            if (empty($errors)) {
                $user = user::where('email', '=', $request->getParam('email'))->first();
                if (!is_null($user)) {
                    if (password_verify($request->getParam('password'), $user->password)) {
                        $this->createSession('user', ['id' => $user->id, 'gest' => $user->gestionnaire]);
                        $this->flash('success', "Connexion réussie avec succès.");
                        return $this->redirect($response, 'utilisateur.compte', ['id' => $user->id]);
                    } else {
                        $this->flash('errors', $errors);
                        return $this->redirect($response, 'utilisateur.connexion.form', $args, 400);
                    }
                } else {
                    $this->flash('error', "Utilisateur introuvable.");
                    return $this->redirect($response, 'index');
                }
            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'utilisateur.connexion.form', $args, 400);
            }
        } else {
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        }
    }

    public function deconnexion(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
            $this->flash('info', 'Déconnexion réussie avec succès.');
            return $this->redirect($response, 'index');
        } else {
            return $this->redirect($response, 'index');
        }
    }
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
        $tab = \charly\models\User::where('nom', 'LIKE', "$search%")->get();
        $users = array();
        foreach ($tab as $u){
            array_push($users, $u->nom);
        }
        echo json_encode($users);
    }

    public function compte(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $user = User::where('id', $args['id'])->first();
            $this->render($response, 'utilisateurs/compte', ['user' => $user]);
        } else {
            return $this->redirect($response, 'index');
        }
    }

    public function avatar(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $user = User::where('id', '=', $_SESSION['user']['id'])->first();
            $user->img = $request->getParam('avatar');
            $user->save();
            $this->flash('info', "Avatar changé avec succès.");
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        } else {
            return $this->redirect($response, 'index');
        }
    }





    public function retrieveId(RequestInterface $request, ResponseInterface $response, $args)
    {
        $search = $args['name'];
        $tab = \charly\models\User::where('nom', '=', $search)->first();
        echo $tab->img;
    }


    public function editer(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $errors = [];
            if (!Validator::email()->validate($request->getParam('email'))) {
                $errors['email'] = "Votre email n'est pas valide.";
            }

            if (!empty($request->getParam('password'))) {
                if ($request->getParam('password') == $request->getParam('password_verify')) {

                } else {
                    $errors['email'] = "Les deux mots de passe ne sont pas identique.";
                }
            }

            if (empty($errors)) {
                $user = User::where('id', '=', $_SESSION['user']['id'])->first();
                if (empty($request->getParam('password'))) {
                    $user->email = $request->getParam('email');
                } else {
                    $user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT, ['cost' => 10]);
                }
                $user->save();
                $this->flash('info', "Mise à jour réussie.");
                return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']], 400);
            }

            $this->flash('info', "Mise à jour réussie.");
            return $this->redirect($response, 'utilisateur.compte', ['id' => $_SESSION['user']['id']]);
        } else {
            return $this->redirect($response, 'index');
        }
    }


    public function rateUser(RequestInterface $request, ResponseInterface $response, $args)
    {
        if(isset($_SESSION['user'])){
            if(isset($args['id']) && isset($args['note'])){
                $g = User::where('id',$args['id'])->first();
                if(!is_null($g)){
                    $n = new NotationUser();
                    $n->idUser=$args['id'];
                    $n->note=$args['note'];
                    $n->save();

                    $g->moy=round($g->notations()->avg('note'),1);
                    $g->save();

                    return $this->redirect($response, 'detailsUser',['id' => $g->id]);
                }
            }
        }else {
            $this->flash('info', 'Vous devez être connecté');
            return $this->redirect($response, 'utilisateur.connexion.form');
        }
    }

}