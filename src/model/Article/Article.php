<?php

namespace maxime\model\Article;

use maxime\model\traits\IdTrait;
use maxime\model\traits\JsonSerializeTrait;
use JsonSerializable;

/**
 * @Entity
 */
class Article implements JsonSerializable
{
    use IdTrait;

    /**
     * @var bool
     * @Column(type="boolean", nullable=true)
     */
    protected $isTexte;

    /**
     * @var bool
     * @Column(type="boolean", nullable=true)
     */
    protected $isImages;

    /**
     * @var bool
     * @Column(type="boolean", nullable=true)
     */
    protected $isVideo;

    /**
     * @var string
     * @Column(type="string", length=64000)
     */
    protected $corps;

    /**
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $titre;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $date;


    use JsonSerializeTrait;

    /**
     * @return string
     */
    public function getCorps()
    {
        return filter_var($this->corps, FILTER_SANITIZE_STRING);
    }

    /**
     * @param string $corps
     *
     * @return Article
     */
    public function setCorps($corps)
    {
        $this->corps = filter_var($corps, FILTER_SANITIZE_STRING);

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
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTexte()
    {
        return $this->isTexte;
    }

    /**
     * @param bool $isTexte
     * @return Article
     */
    public function setIsTexte($isTexte)
    {
        $this->isTexte = $isTexte;

        return $this;
    }

    /**
     * @return bool
     */
    public function isImages()
    {
        return $this->isImages;
    }

    /**
     * @param bool $isImages
     * @return Article
     */
    public function setIsImages($isImages)
    {
        $this->isImages = $isImages;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return $this->isVideo;
    }

    /**
     * @param bool $isVideo
     * @return Article
     */
    public function setIsVideo($isVideo)
    {
        $this->isVideo = $isVideo;

        return $this;
    }
}
