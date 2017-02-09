<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 22/01/2017
 * Time: 16:09
 */

namespace charly\controllers;

use coolracing\models\Epreuve;
use coolracing\models\Participant;
use coolracing\models\Participe;
use Dompdf\Dompdf;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

/**
 * Controller d'epreuves
 * Class EpreuvesController
 * @package coolracing\controllers
 */
class EpreuvesController extends BaseController
{

    /**
     * Fonction de rendu du formulaire d'inscription
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function registerForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        $epreuve = Epreuve::where('idEpreuve', '=', $args['numepreuve'])->first();
        if (is_null($epreuve)) {
            $this->flash('info', "Impossible de trouver l'épreuve.");
            return $this->redirect($response, 'index');
        } else {
            if ($epreuve->close == 1) {
                $this->flash('info', "Les inscriptions sont fermées pour l'épreuve : {$epreuve->nom}");
                return $this->redirect($response, 'index');
            } else {
                $praticipants = Participe::where('idEpreuve', '=', $epreuve->idEpreuve)->count();
                if ($praticipants < $epreuve->nbParticipants) {
                    $epreuve['evenement'] = $epreuve->evenement()->nom;
                    $epreuve['categorie'] = $epreuve->categorie()->nom;
                    $epreuve['placesDispo'] = $epreuve->nbParticipants - $praticipants;
                    $this->render($response, 'epreuves/register', ['epreuve' => $epreuve]);
                } else {
                    $this->flash('info', "Les inscriptions sont complètes pour l'épreuve : {$epreuve->nom}");
                    return $this->redirect($response, 'index');
                }
            }
        }
    }

    /**
     * Fonction d'inscription a une epreuve
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function register(RequestInterface $request, ResponseInterface $response, $args)
    {
        $epreuve = Epreuve::where('idEpreuve', '=', $args['numepreuve'])->first();
        if (!is_null($epreuve)) {
            if ($epreuve->close == 1) {
                $this->flash('info', "Les inscriptions sont fermées pour l'épreuve : {$epreuve->nom}");
                return $this->redirect($response, 'index');
            } else {
                $praticipants = Participe::where('idEpreuve', '=', $epreuve->idEpreuve)->count();
                if ($praticipants < $epreuve->nbParticipants) {
                    $errors = [];
                    if (!Validator::email()->validate($request->getParam('email'))) {
                        $errors['email'] = "Votre email n'est pas valide.";
                    }
                    if (!Validator::stringType()->notEmpty()->validate($request->getParam('nom'))) {
                        $errors['nom'] = "Vous devez précisez un nom.";
                    }
                    if (!Validator::stringType()->notEmpty()->validate($request->getParam('prenom'))) {
                        $errors['prenom'] = "Vous devez précisez un prenom.";
                    }
                    if (empty($errors)) {
                        $idParticipant = Participant::create($request->getParams());
                        Participe::create(['idParticipant' => $idParticipant->idParticipant, 'idEpreuve' => $epreuve->idEpreuve]);
                        $this->flash('success', "Inscription réussie pour l'épeuve : {$epreuve->nom}.");
                        return $this->redirect($response, 'index');
                    } else {
                        $this->flash('errors', $errors);
                        return $this->redirect($response, 'epreuve.inscription.form', $args, 400);
                    }
                } else {
                    $this->flash('info', "Les inscriptions sont complètes pour l'épreuve : {$epreuve->nom}");
                    return $this->redirect($response, 'index');
                }
            }
        } else {
            $this->flash('info', "Impossible de trouver l'épreuve.");
            return $this->redirect($response, 'index');
        }
    }

    /**
     * Fonction de rendu de la vue de formulaire d'upload de resultats
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function uploadForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        $epreuve = Epreuve::where('idEpreuve', '=', $args['numepreuve'])->first();
        if (!is_null($epreuve)) {
            $this->render($response, 'epreuves/upload', ['epreuve' => $epreuve]);
        } else {
            return $this->redirect($response, 'index');
        }
    }

    /**
     * Fonction de traitement du formulaire d'upload
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function upload(RequestInterface $request, ResponseInterface $response, $args)
    {
        $epreuve = Epreuve::where('idEpreuve', '=', $args['numepreuve'])->first();
        if (!is_null($epreuve)) {
            $fichier = $request->getUploadedFiles();
            if (!empty($fichier)) {
                $resultats = $fichier['fichier'];

                if ($resultats->getError() === UPLOAD_ERR_OK) {
                    $nom = 'resultats_' . $epreuve->nom . '_' . $epreuve->idEpreuve . '.pdf';
                    $resultats->moveTo(UPLOADS . DS . $nom);
                    $this->flash('info', "Fichier de résultats uploadé.");

                    $epreuve->resultats = 1;
                    $epreuve->save();

                    return $this->redirect($response, 'index');
                }
            }
        } else {
            return $this->redirect($response, 'index');
        }
    }

}