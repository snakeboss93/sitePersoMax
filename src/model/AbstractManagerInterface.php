<?php

namespace ifacebook\model;

/**
 * Interface AbstractManagerInterface
 *
 * @author PEREZ Pierre
 */
interface AbstractManagerInterface
{
    /**
     * AbstractManager constructor.
     */
    public function __construct();

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findAll($limit = null, $offset = null);

    /**
     * @return Object
     */
    public function findOneLast();

    /**
     * Renvoie un objet par son id.
     *
     * @param int $id
     *
     * @return Object
     */
    public function findOneById($id);

    /**
     * Permet de sauvegarder un objet.
     *
     * @param $object
     */
    public function save($object);

    /**
     * Permet de update un objet.
     *
     * @param $object
     */
    public function update($object);
}
