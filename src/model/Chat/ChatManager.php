<?php

namespace ifacebook\model\Chat;

use Doctrine\ORM\EntityRepository;
use ifacebook\model\AbstractManager;

/**
 * Class ChatManager
 *
 * @author Loïc TORO
 */
class ChatManager extends AbstractManager
{
    /** @var EntityRepository $chatRepository */
    protected $chatRepository;

    /**
     * ChatManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->chatRepository = $this->em->getRepository(Chat::class);
    }

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        return $this->chatRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
    }

    /**
     * @return Chat|Object
     */
    public function findOneLast()
    {
        return $this->chatRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un chat par son id.
     *
     * @param int $id
     *
     * @return Object|Chat
     */
    public function findOneById($id)
    {
        return $this->chatRepository->findOneBy(['id' => $id]);
    }
}
