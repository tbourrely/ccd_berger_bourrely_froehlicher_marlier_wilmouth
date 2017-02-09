<?php

namespace charly\controllers;

use charly\models\user;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class UtilisateursController extends BaseController
{

    public function inscriptionForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->render($response, 'utilisateurs/inscription');
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
            if (empty($errors)) {
                $params = $request->getParams();
                unset($params['csrf_name']);
                unset($params['csrf_value']);
                $idUser = user::create($params);
                $this->flash('success', "Inscription réussie avec succès.");
                return $this->redirect($response, 'utilisateur.compte', ['id' => $idUser->id]);
            } else {
                $this->flash('errors', $errors);
                return $this->redirect($response, 'inscription.form', $args, 400);
            }
        }
        return $this->redirect($response, 'utilisateur.compte', $_SESSION['user']);
    }

    public function compte(RequestInterface $request, ResponseInterface $response, $args)
    {
        var_dump($args);
        die();
    }

}