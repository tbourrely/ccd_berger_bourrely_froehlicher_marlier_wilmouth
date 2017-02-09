<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 09/02/2017
 * Time: 15:38
 */

namespace charly\controllers;

use charly\models\Groupe;
use charly\models\Invitation;
use charly\models\Logement;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class GestionController extends BaseController
{

    public function index(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['gest'] === 1) {
                $logements = Logement::with('groupes', 'groupes.invitation', 'groupes.invitation.user')->get();
                $this->render($response, 'gestion/index', ['logements' => $logements]);
            } else {
                $this->flash('error', 'Vous devez être gestionnaire pour accèder a cette page.');
                return $this->redirect($response, 'index');
            }
        } else {
            return $this->redirect($response, 'index');
        }
    }

    public function valider(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $groupeId = $args['groupe'];

            $groupe = Groupe::where('id', '=', $groupeId)->first();
            $groupe->status = 'cloture';
            $groupe->save();

            $logement = Logement::where('id', '=', $groupe->idLogement)->first();
            $logement->occupe = 1;
            $logement->save();

            $groupes = Groupe::where('idLogement', '=', $logement->id)->get();
            foreach ($groupes as $grp) {
                if ($grp->id != $groupeId) {
                    $grp->status = 'refusé';
                    $grp->save();
                }
            }

            $this->flash('success', 'Groupe valider.');
            return $this->redirect($response, 'gestion.index');
        } else {
            return $this->redirect($response, 'index');
        }
    }

    public function refuser(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            $groupeId = $args['groupe'];
            $groupe = Groupe::where('id', '=', $groupeId)->first();
            $groupe->status = 'refusé';
            $groupe->save();
            $this->flash('success', 'Groupe refusé.');
            return $this->redirect($response, 'gestion.index');

        } else {
            return $this->redirect($response, 'index');
        }
    }

    public function ajouterLogementForm(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['gest'] === 1) {
                $this->render($response, 'gestion/ajouterLogement');
            } else {
                $this->flash('error', 'Vous devez être gestionnaire pour accèder a cette page.');
                return $this->redirect($response, 'index');
            }
        } else {
            return $this->redirect($response, 'index');
        }
    }

    public function ajouterLogement(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['gest'] === 1) {
                $errors = [];
                if (!Validator::intVal()->validate($request->getParam('places'))) {
                    $errors['places'] = "Veuillez spécifier un nombre de place";
                }
                if ($request->getParam('places') <= 0) {
                    $errors['places'] = "Veuillez entrer un nombre positif";
                }

                if(empty($errors)) {
                    $fichier = $request->getUploadedFiles();
                    if (!empty($fichier)) {
                        $resultats = $fichier['image'];

                        if ($resultats->getError() === UPLOAD_ERR_OK) {

                            $logement = new Logement();
                            $logement->places = $request->getParam('places');
                            $logement->save();

                            $nom = $logement->id . '.jpg';
                            $resultats->moveTo(ASSETS . DS . 'img' . DS . 'apart' . DS . $nom);
                            $this->flash('info', "Logement ajouté");
                            return $this->redirect($response, 'gestion.logement.ajouter.form');
                        }
                    }
                } else {
                    $this->flash('errors', $errors);
                    return $this->redirect($response, 'gestion.logement.ajouter.form', $args, 400);
                }
            } else {
                $this->flash('error', 'Vous devez être gestionnaire pour accèder a cette page.');
                return $this->redirect($response, 'index');
            }
        } else {
            return $this->redirect($response, 'index');
        }
    }
}