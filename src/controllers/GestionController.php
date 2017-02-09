<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 09/02/2017
 * Time: 15:38
 */

namespace charly\controllers;

use charly\models\Groupe;
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
}