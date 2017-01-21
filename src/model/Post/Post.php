<?php

namespace ifacebook\model\Post;

use ifacebook\model\traits\IdTrait;
use ifacebook\model\traits\JsonSerializeTrait;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="fredouil.post")
 */
class Post implements JsonSerializable
{
    use IdTrait;

    /**
     * @Column(type="string", length=2000)
     * @var string $texte
     */
    protected $texte;

    /**
     * @Column(type="datetime")
     * @var \DateTime $date
     */
    protected $date;

    /**
     * @Column(type="string", length=200)
     * @var string $image
     */
    protected $image;

    use JsonSerializeTrait;

    /**
     * @return string
     */
    public function getTexte()
    {
        return strip_tags(filter_var($this->texte, FILTER_SANITIZE_STRING));
    }

    /**
     * @param string $texte
     *
     * @return Post
     */
    public function setTexte($texte)
    {
        $this->texte = strip_tags(filter_var($texte, FILTER_SANITIZE_STRING));

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return null !== $this->image && '' !== $this->image ? $this->image : 'images/error.png';
    }

    /**
     * @return string
     */
    public function hasImage()
    {
        return null !== $this->image && '' !== $this->image ? true : false;
    }

    /**
     * @param string $image
     *
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
