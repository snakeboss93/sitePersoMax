<?php

namespace ifacebook\model\traits;

use Doctrine\ORM\Mapping as ORM;
use ifacebook\model\Post\Post;

/**
 * trait PostTrait.
 */
trait PostTrait
{
    /**
     * @OneToOne(targetEntity="ifacebook\model\Post\Post")
     * @JoinColumn(nullable=false, name="post", referencedColumnName="id")
     * @var Post $post
     */
    protected $post;

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }
}
