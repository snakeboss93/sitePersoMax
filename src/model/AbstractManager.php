<?php

namespace ifacebook\model;

use Doctrine\ORM\EntityManager;
use lib\core\DbConnection;

/**
 * abstract Class AbstractManager
 *
 * @author Pierre PEREZ
 */
abstract class AbstractManager implements AbstractManagerInterface
{
    /** @var EntityManager */
    protected $em;

    /**
     * AbstractManager constructor.
     */
    public function __construct()
    {
        $this->em = DbConnection::getInstance()->getEntityManager();
    }

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public abstract function findAll($limit = null, $offset = null);

    /**
     * @return Object
     */
    public abstract function findOneLast();

    /**
     * Renvoie un objet par son id.
     *
     * @param int $id
     *
     * @return Object
     */
    public abstract function findOneById($id);

    /**
     * Permet de sauvegarder un objet.
     *
     * @param $object
     */
    public function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     * Permet de update un objet.
     *
     * @param $object
     */
    public function update($object)
    {
        $this->em->merge($object);
        $this->em->flush();
    }
}
