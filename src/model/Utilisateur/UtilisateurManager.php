<?php

namespace maxime\model\Utilisateur;

use Doctrine\ORM\EntityRepository;
use maxime\model\AbstractManager;

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
     * @param $login
     * @param $pass
     *
     * @return Object|Utilisateur
     */
    public function findConnection($login, $pass)
    {
        return $this->userRepository->findOneBy(
            ['identifiant' => $login, 'pass' => password_hash(password_hash($pass, PASSWORD_DEFAULT), PASSWORD_DEFAULT)]
        );
    }
}
