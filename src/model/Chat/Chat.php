<?php

namespace ifacebook\model\Chat;

use ifacebook\model\traits\IdTrait;
use ifacebook\model\traits\JsonSerializeTrait;
use ifacebook\model\traits\PostTrait;
use ifacebook\model\Utilisateur\Utilisateur;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="fredouil.chat")
 *
 * @author: Loic TORO
 */
class Chat implements JsonSerializable
{
    use IdTrait;

    use PostTrait;

    /**
     * @ManyToOne(targetEntity="ifacebook\model\Utilisateur\Utilisateur")
     * @JoinColumn(nullable=false, name="emetteur", referencedColumnName="id")
     * @var Utilisateur $emetteur
     */
    protected $emetteur;

    use JsonSerializeTrait;

    /**
     * @return Utilisateur
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * @param Utilisateur $emetteur
     *
     * @return Chat
     */
    public function setEmetteur($emetteur)
    {
        $this->emetteur = $emetteur;

        return $this;
    }
}
