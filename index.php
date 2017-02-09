<?php

// Demarrage de la session
session_start();

// Importation de l'autoloader
require 'vendor/autoload.php';

// Configuration de la connexion a la base de donnees
\charly\DatabaseFactory::setConfig();
\charly\DatabaseFactory::makeConnection();

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
    $view = new \Slim\Views\Twig(SRC . DS . 'views', [
        'cache' => false // Pas de cache sur les vues
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

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

$app->get('/', \charly\controllers\ExempleController::class . ':index')->setName('index');

$app->group('/utilisateur', function() {
    $this->get('/list', \charly\controllers\UtilisateursController::class . ':listUsers')->setName('listUsers');
    $this->get('/inscription', \charly\controllers\UtilisateursController::class . ':inscriptionForm')->setName('inscription.form');
});

$app->group('/logement',function (){
    $this->get('/list',\charly\controllers\LogementController::class.':listLogement')->setName('listLogement');
});

/*
$app->get('/', \charly\controllers\EvenementsController::class . ':index')->setName('index');

$app->group('/evenements', function () {
    $this->get('/{order:[a-z]+}', \coolracing\controllers\EvenementsController::class . ':index')->setName('evenements');
    $this->post('/recherche', \coolracing\controllers\EvenementsController::class . ':search')->setName('evenements.recherche');
    $this->get('/recherche/{recherche}', \coolracing\controllers\EvenementsController::class . ':resultats')->setName('evenements.recherche.resultats');
});
*/

$app->run();
