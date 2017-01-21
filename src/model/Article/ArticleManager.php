<?php

namespace maxime\model\Article;

use Doctrine\ORM\EntityRepository;
use maxime\model\AbstractManager;

/**
 * Class ArticleManager
 */
class ArticleManager extends AbstractManager
{
    /** @var EntityRepository $postRepository */
    protected $postRepository;

    /**
     * ArticleManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->postRepository = $this->em->getRepository(Article::class);
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
     * @return Article|Object
     */
    public function findOneLast()
    {
        return $this->postRepository->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Renvoie un Article par son id.
     *
     * @param int $id
     *
     * @return Object|Article
     */
    public function findOneById($id)
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }
}
