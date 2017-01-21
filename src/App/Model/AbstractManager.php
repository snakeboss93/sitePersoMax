<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

/**
 * AbstractManager fournissant une implémentation classique des CRUD.
 */
abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param object $object
     */
    public function saveObject($object)
    {
        if (null === $object->getId()) {
            $this->em->persist($object);
        }
        $this->em->flush();
    }

    /**
     * @param object $object
     */
    public function create($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     * @param object $object
     */
    public function update($object)
    {
        $this->em->merge($object);
        $this->em->flush();
    }

    /**
     * @param object $object
     */
    public function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }
}
