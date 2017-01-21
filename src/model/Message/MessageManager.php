<?php

namespace ifacebook\model\Message;

use Doctrine\ORM\EntityRepository;
use ifacebook\model\AbstractManager;
use ifacebook\model\Utilisateur\Utilisateur;

/**
 * Class MessageManager
 */
class MessageManager extends AbstractManager
{
    /** @var EntityRepository $messageRepository */
    protected $messageRepository;

    /**
     * MessageManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->messageRepository = $this->em->getRepository(Message::class);
    }

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        return $this->messageRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
    }

    /**
     * @return message|Object
     */
    public function findOneLast()
    {
        return $this->messageRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un message par son id.
     *
     * @param int $id
     *
     * @return Object|message
     */
    public function findOneById($id)
    {
        return $this->messageRepository->findOneBy(['id' => $id]);
    }

    /**
     * Renvoie un tableau de message par id d'emetteur.
     *
     * @param int $id
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findByEmetteurId($id, $limit = null, $offset = null)
    {
        return $this->messageRepository->findBy(['destinataire' => $id], ['id' => 'DESC'], $limit, $offset);
    }

    /**
     * Message constructor par recopie.
     *
     * @param Message $origin
     * @param Utilisateur $proprietaire
     *
     * @return Message
     */
    public function recopieConstruct($origin, $proprietaire)
    {
        $msg = new Message();
        $msg->setAime(0);
        $msg->setParent($origin->getEmetteur());
        $msg->setPost($origin->getPost());
        $msg->setDestinataire($proprietaire);
        $msg->setEmetteur($proprietaire);

        return $msg;
    }
}
