<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 22/01/2017
 * Time: 16:09
 */

namespace coolracing\controllers;

use coolracing\models\Evenement;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

/**
 * Controller d'evenements
 * Class EvenementsController
 * @package coolracing\controllers
 */
class EvenementsController extends BaseController
{

    /**
     * Fonction de rendu des evenements
     * Permet de lister tous les evenements
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function index(RequestInterface $request, ResponseInterface $response, $args)
    {
        $evenements = Evenement::get()->sortBy('dateDeb');
        if (!empty($args)) {
            if ($args['order'] === 'desc') {
                $evenements->sortByDesc('dateDeb');
            }
        }
        $this->render($response, 'evenements/index', ['evenements' => $evenements]);
    }

    /**
     * Fonction de rendu d'un seul evenement
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function view(RequestInterface $request, ResponseInterface $response, $args)
    {
        $evenement = Evenement::where('idEvenement', '=', $args['id'])->first();
        $this->render($response, 'evenements/view', ['evenement' => $evenement]);
    }

    /**
     * Recherche d'evenements par mot clé
     * recherche dans le nom et la description
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function search(RequestInterface $request, ResponseInterface $response, $args){
        $data = $request->getParams();
        if (!empty($data)) {
            $recherche = urlencode(trim($data['keyword']));
            return $this->redirect($response, 'evenements.recherche.resultats', ['recherche' => $recherche]);
        } else {
            $this->flash('info', "Impossible de trouver ce que vous recherchez.");
            return $this->redirect($response, 'index');
        }
    }

    /**
     * Fonction d'affichage des resultats de recherche d'evenements
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function resultats(RequestInterface $request, ResponseInterface $response, $args){
        if (!empty($args)) {
            $keyword = trim($args['recherche']);
            $evenements = Evenement::where('nom', 'LIKE', "%$keyword%")->orWhere('desc', 'LIKE', "%$keyword%")->get();
            if (!is_null($evenements)) {
                $this->render($response, 'evenements/search', ['evenements' => $evenements]);
            } else {
                $this->flash('info', "Impossible de trouver ce que vous recherchez.");
                return $this->redirect($response, 'index');
            }
        } else {
            $this->flash('info', "Impossible de trouver ce que vous recherchez.");
            return $this->redirect($response, 'index');
        }
    }

    // CREATION D'UN EVENEMENT | SANS LIEN AVEC LE COMPTE ORGANISATEUR
    /**
     * Fonction de rendu du formulaire de creation d'evenement
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function createForm(RequestInterface $request, ResponseInterface $response, $args){
        $this->render($response, 'evenements/create');
    }

    /**
     * Fonction de traitement du formulaire de creation d'evenement
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function create(RequestInterface $request, ResponseInterface $response, $args){
        $params = $request->getParams();
        $errors = [];

        if(!Validator::stringType()->notEmpty()->validate($params['nom']))
            $errors['nom'] = "Erreur dans le nom de l'événement !";
        if(!Validator::stringType()->notEmpty()->validate($params['description']))
            $errors['description'] = "Erreur de description de l'événement !";
        if(!Validator::stringType()->notEmpty()->validate($params['ville']))
            $errors['ville'] = "Erreur dans saisie du nom de la ville !";
        if(!Validator::date('d/m/Y')->notEmpty()->validate($params['dateDeb']))
            $errors['dateDeb'] = "Erreur de saisie de la date de début de l'événement !";
        if(!Validator::date('d/m/Y')->notEmpty()->validate($params['dateFin']))
            $errors['dateFin'] = "Erreur de saisie de la date de fin de l'événement !";

        $dateDeb = \DateTime::createFromFormat('d/m/Y', $params['dateDeb'])->format('Y-m-d');


        if(!empty($errors)){
            $this->flash('errors', $errors);
            return $this->redirect($response, 'evenement.create.form');
        }else{
            $dateDeb = \DateTime::createFromFormat('d/m/Y', $params['dateDeb'])->format('Y-m-d');
            $dateFin = \DateTime::createFromFormat('d/m/Y', $params['dateFin'])->format('Y-m-d');
            $id = Evenement::updateOrCreate([ // si un Evenement existe avec les parametre suivants -> update, sinon create
                "nom" => $params['nom'],
                "dateDeb" => $dateDeb,
                "dateFin" => $dateFin,
                "lieu" => $params['ville']
            ],[
                "idCompte" => 1, // par defaut
                "desc" => $params['description'],
                "termine" => 0 // par defaut car evenement non terminé
            ])->idEvenement;

            if(!is_null($id)){
                $this->flash('success', "Création de l'épreuve réussie");
                return $this->redirect($response, 'evenement', ['id' => $id]);
            }
            else{
                $this->flash('error', "Création de l'épreuve impossible");
                return $this->redirect($response, 'evenement.create.form');
            }
        }
    }

}