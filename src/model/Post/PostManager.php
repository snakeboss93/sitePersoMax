<?php

namespace ifacebook\model\Post;

use Doctrine\ORM\EntityRepository;
use ifacebook\model\AbstractManager;

/**
 * Class PostManager
 */
class PostManager extends AbstractManager
{
    /** @var EntityRepository $postRepository */
    protected $postRepository;

    /**
     * PostManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->postRepository = $this->em->getRepository(Post::class);
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
     * @return Post|Object
     */
    public function findOneLast()
    {
        return $this->postRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un Post par son id.
     *
     * @param int $id
     *
     * @return Object|Post
     */
    public function findOneById($id)
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }
}
