<?php

namespace lib\core;

define('HOST', 'localhost');
//define('HOST', 'mysql.hostinger.fr');
define('USER', 'root');
//define('USER', 'u606094034_root');
define('PASS', 'root');
//define('PASS', 'RbNER|`u!^OeQK*7[c');
define('DB', 'u606094034_maxim');
define('DRIVER', 'mysql');

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Class DbConnection
 * Classe données par doctrine.
 * Doctrine est un ORM (Mapping objet-relationnel) de base de données
 */
class DbConnection
{
    private static $instance = null;
    private static $entityManager;
    private $error = null;

    /**
     * DbConnection constructor.
     */
    private function __construct()
    {
        $config = Setup::createAnnotationMetadataConfiguration(["../../src/model/"], true);

        $param = [
            'dbname' => DB,
            'user' => USER,
            'password' => PASS,
            'host' => HOST,
            'driver' => DRIVER,
        ];

        try {
            self::$entityManager = EntityManager::create($param, $config);
        } catch (Exception $e) {
            echo "Probleme connexion base de données:".$e->getMessage();
            $this->error = $e->getMessage();
        }
    }

    /**
     * @return DbConnection|null
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new DbConnection();
        }

        return self::$instance;
    }

    /**
     * Permet de fermet la connection de la DB.
     */
    public function closeConnection()
    {
        self::$instance = null;
    }

    /**
     * @return EntityManager|null
     */
    public function getEntityManager()
    {
        if (!empty(self::$entityManager)) {
            return self::$entityManager;
        } else {
            return null;
        }
    }

    /**
     *
     */
    public function __clone()
    {
    }

    /**
     * @return null|string
     */
    public function getError()
    {
        return $this->error;
    }
}
