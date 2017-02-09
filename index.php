<?php
use charly\DatabaseFactory;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use charly\controllers\GroupeController;
use charly\controllers\ValidationController;

// Demarrage de la session
session_start();

// Importation de l'autoloader
require 'vendor/autoload.php';

// Configuration de la connexion a la base de donnees
DatabaseFactory::setConfig();
DatabaseFactory::makeConnection();

// Variables globales
define('DS', DIRECTORY_SEPARATOR);
define('SRC', __DIR__ . DS . 'src');
define('TMP', __DIR__ . DS . 'tmp');

// Initialisation de Slim
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true // Affichage des erreurs
    ]
]);

// Initialisation du container
$container = $app->getContainer();

// Initialisation des vues dans le container
$container['views'] = function ($container) {
    $view = new Twig(SRC . DS . 'views', [
        'cache' => false // Pas de cache sur les vues
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    return $view;
};

// Initialisation des messages flash dans le container
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Initialisation de la protection csrf dans le container

$container['csrf'] = function () {
    return new Slim\Csrf\Guard();
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->views->render($response, 'errors/404.html.twig');
    };
};

// Ajouts du Middleware de messages flash
$app->add(new \charly\middlewares\FlashMiddleware($container->views->getEnvironment()));
// Ajouts du Middleware de valeurs persistantes dans les fomulaires
$app->add(new \charly\middlewares\PersistentValuesMiddleware($container->views->getEnvironment()));
// Ajouts du Middleware de protection csrf
$app->add(new \charly\middlewares\CsrfMiddleware($container->views->getEnvironment(), $container->csrf));
$app->add($container->get('csrf'));
// Ajouts du Middleware de verification de connexion
$app->add(new \charly\middlewares\AuthMiddleware($container->views->getEnvironment()));

$app->get('/', \charly\controllers\LogementController::class.':listLogement')->setName('index');

$app->group('/utilisateur', function() {
    $this->get('/list', \charly\controllers\UtilisateursController::class . ':listUsers')->setName('listUsers');
    $this->get('/list-json/{search}', \charly\controllers\UtilisateursController::class . ':listUsersJson')->setName('listUsersJson');
    $this->get('/name/{name}', \charly\controllers\UtilisateursController::class . ':retrieveId')->setName('retrieveId');
    $this->get('/details/{id:[0-9]+}', \charly\controllers\UtilisateursController::class . ':detailsUser')->setName('detailsUser');
    $this->get('/inscription', \charly\controllers\UtilisateursController::class . ':inscriptionForm')->setName('utilisateur.inscription.form');
    $this->post('/inscription', \charly\controllers\UtilisateursController::class . ':inscription')->setName('utilisateur.inscription');
    $this->get('/{id:[0-9]+}', \charly\controllers\UtilisateursController::class . ':compte')->setName('utilisateur.compte');
    $this->get('/connexion', \charly\controllers\UtilisateursController::class . ':connexionForm')->setName('utilisateur.connexion.form');
    $this->post('/connexion', \charly\controllers\UtilisateursController::class . ':connexion')->setName('utilisateur.connexion');
    $this->get('/deconnexion', \charly\controllers\UtilisateursController::class . ':deconnexion')->setName('utilisateur.deconnexion');
    $this->post('/edit/avatar', \charly\controllers\UtilisateursController::class . ':avatar')->setName('utilisateur.avatar');
    $this->post('/edit', \charly\controllers\UtilisateursController::class . ':editer')->setName('utilisateur.edit');
    $this->get('/rate/{id:[0-9]+}/{note:[0-9]+}', \charly\controllers\UtilisateursController::class . ':rateUser')->setName('rateUser');
});

$app->group('/logement',function (){
    $this->get('/list[/{filter1}]',\charly\controllers\LogementController::class.':listLogement')->setName('listLogement');
    $this->get('/details/{id:[0-9]+}', \charly\controllers\LogementController::class . ':detailsLogement')->setName('detailsLogement');
    $this->get('/rate/{id:[0-9]+}/{note:[0-9]+}', \charly\controllers\LogementController::class . ':rateLogement')->setName('rateLogement');
});

$app->group('/gestion',function (){
    $this->get('/index', \charly\controllers\GestionController::class . ':index')->setName('gestion.index');
});

/*
$app->get('/', \charly\controllers\EvenementsController::class . ':index')->setName('index');

$app->group('/evenements', function () {
    $this->get('/{order:[a-z]+}', \coolracing\controllers\EvenementsController::class . ':index')->setName('evenements');
    $this->post('/recherche', \coolracing\controllers\EvenementsController::class . ':search')->setName('evenements.recherche');
    $this->get('/recherche/{recherche}', \coolracing\controllers\EvenementsController::class . ':resultats')->setName('evenements.recherche.resultats');
});
*/

$app->group('/group', function(){
    $this->get('/create', GroupeController::class . ':interfaceCreationGroupe')->setName('createGroup');
    $this->post('/create', GroupeController::class . ':postCreerGroupe')->setName('createGroupForm');
    $this->get('/view', GroupeController::class . ':interfaceViewGroupe')->setName('viewGroup');

    $this->get('/add/{id}', GroupeController::class . ':add')->setName('addGroup');

    $this->post('/validate', ValidationController::class . ':validerGroupe')->setName('validateGroup');
    $this->post('/generateURL/{id:[0-9]+}', ValidationController::class . ':genererURL')->setName('generateURL');
    $this->post('/delete', GroupeController::class . ':supprimerUser')->setName('supprimerUser');
    $this->post('/validateComplete', ValidationController::class . ':validerGroupeComplet')->setName('validateGroupComplete');
    $this->get('/join/{url}', ValidationController::class . ':rejoindreGroupe')->setName('joinGroup');
    $this->post('/acceptInvitation/{id}', ValidationController::class . ':accepterInvitation')->setName('acceptInvitation');
    $this->post('/refuseInvitation/{id}', ValidationController::class . ':refuserInvitation')->setName('refuseInvitation');



});



$app->post('/group/modif', GroupeController::class . ':postAjoutLogement')->setName('ajoutLogement');

$app->post('/group/supLogement', GroupeController::class . ':postSupLogement')->setName('supLogement');

$app->run();
