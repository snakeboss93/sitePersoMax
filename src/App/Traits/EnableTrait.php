<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EnableTrait.
 */
trait EnableTrait
{
    /**
     * Determine si l'objet est activer ou non (visible ou non) afin de la masquer.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enable = true;

    /**
     * @param bool $isEnable
     *
     * @return $this
     */
    public function setEnable($isEnable = true)
    {
        $this->enable = $isEnable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enable;
    }
}
