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

            $invitations = Invitation::where('idGroupe', '=', $groupeId)->delete();

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
            $invitations = Invitation::where('idGroupe', '=', $groupeId)->delete();
            $groupe = Groupe::where('id', '=', $groupeId)->first();
            $groupe->status = 'refusé';
            $groupe->save();
            $this->flash('success', 'Groupe refusé.');
            return $this->redirect($response, 'gestion.index');

        } else {
            return $this->redirect($response, 'index');
        }
    }
}