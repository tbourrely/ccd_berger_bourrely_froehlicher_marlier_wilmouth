<?php

namespace charly;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Classe de connection a la base de donees
 * Class DatabaseFactory
 * @package coolracing
 */
class DatabaseFactory
{

    private static $dbConfig;

    /**
     * Chargement du fichier de configuration
     */
    public static function setConfig() {
        if (is_null(self::$dbConfig)) {
            $conf = require(__DIR__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'db.conf.php');
            self::$dbConfig = $conf[$conf['default']];
        }
    }

    /**
     * Creation de la connexion a la base de donnees
     */
    public static function makeConnection() {
        if (!is_null(self::$dbConfig)) {
            $db = new DB();
            $db->addConnection(
                [
                    'driver'    => self::$dbConfig['driver'],
                    'host'      => self::$dbConfig['host'],
                    'database'  => self::$dbConfig['dbName'],
                    'username'  => self::$dbConfig['user'],
                    'password'  => self::$dbConfig['pass'],
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => ''
                ]
            );
            $db->setAsGlobal();
            $db->bootEloquent();
        }
    }

}