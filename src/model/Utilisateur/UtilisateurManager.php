<?php

namespace ifacebook\model\Utilisateur;

use Doctrine\ORM\EntityRepository;
use ifacebook\model\AbstractManager;

/**
 * Class utilisateurManager
 * @author Pierre PEREZ
 */
class UtilisateurManager extends AbstractManager
{
    /** @var EntityRepository $userRepository */
    protected $userRepository;

    /**
     * UtilisateurManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = $this->em->getRepository(Utilisateur::class);
    }

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        return $this->userRepository->findBy([], ['prenom' => 'ASC'], $limit, $offset);
    }

    /**
     * @return Utilisateur|Object
     */
    public function findOneLast()
    {
        return $this->userRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un Utilisateur par son id.
     *
     * @param int $id
     *
     * @return Object|Utilisateur
     */
    public function findOneById($id)
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    /**
     * Recherche un utilisateur par nom, prenom ou identifant.
     * Renvoie un tableau d'Utilisateur par nom ou prenom.
     *
     * @param string $q
     *
     * @return array
     */
    public function findByNameOrLastName($q)
    {
        $qb = $this->userRepository->createQueryBuilder('u')
            ->where('LOWER(u.nom) LIKE :nameContains')
            ->orWhere('LOWER(u.prenom) LIKE :nameContains')
            ->orWhere('LOWER(u.identifiant) LIKE :nameContains')
            ->setParameter('nameContains', '%'.strtolower($q).'%');

        $items = $qb->getQuery()->getResult();
        $results = [];

        /** @var Utilisateur $item */
        foreach ($items as $item) {
            $tmp = [];
            $tmp['id'] = $item->getId();
            $tmp['fullname'] = $item->getFullName();
            $tmp['img'] = $item->getAvatar();
            $results[] = $tmp;
        }

        return $results;
    }

    /**
     * @param $login
     * @param $pass
     *
     * @return bool|Utilisateur
     */
    public function getUserByLoginAndPass($login, $pass)
    {
        /** @var Utilisateur $user */
        $user = $this->userRepository->findOneBy(['identifiant' => $login, 'pass' => sha1($pass)]);
        if (null === $user) {
            return false;
        }

        return $user;
    }
}
