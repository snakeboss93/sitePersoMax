<?php

namespace maxime\model\Utilisateur;

use maxime\model\traits\IdTrait;
use maxime\model\traits\JsonSerializeTrait;
use JsonSerializable;

/**
 * @Entity
 */
class Utilisateur implements JsonSerializable
{
    use IdTrait;

    /**
     * @Column(type="string", length=45)
     * @var string $identifiant
     */
    protected $identifiant;

    /**
     * @Column(type="string", length=64000)
     * @var string $pass
     */
    protected $pass;

    use JsonSerializeTrait;

    /**
     * @return mixed
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * @param mixed $identifiant
     *
     * @return Utilisateur
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     *
     * @return Utilisateur
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }
}
