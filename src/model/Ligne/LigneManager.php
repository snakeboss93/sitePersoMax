<?php

namespace maxime\model\Ligne;

use Doctrine\ORM\EntityRepository;
use maxime\model\AbstractManager;

/**
 * Class LigneManager
 */
class LigneManager extends AbstractManager
{
    /** @var EntityRepository $postRepository */
    protected $postRepository;

    /**
     * LigneManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->postRepository = $this->em->getRepository(Ligne::class);
    }

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        return $this->postRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
    }

    /**
     * @return Ligne|Object
     */
    public function findOneLast()
    {
        return $this->postRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un Ligne par son id.
     *
     * @param int $id
     *
     * @return Object|Ligne
     */
    public function findOneById($id)
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }
}
