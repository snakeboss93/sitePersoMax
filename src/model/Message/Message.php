<?php

namespace ifacebook\model\Message;

use ifacebook\model\traits\IdTrait;
use ifacebook\model\traits\JsonSerializeTrait;
use ifacebook\model\traits\PostTrait;
use ifacebook\model\Utilisateur\Utilisateur;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="fredouil.message")
 */
class Message implements JsonSerializable
{
    use IdTrait;

    use PostTrait;

    /**
     * @ManyToOne(targetEntity="ifacebook\model\Utilisateur\Utilisateur")
     * @JoinColumn(nullable=false, name="emetteur", referencedColumnName="id")
     * @var Utilisateur $emetteur
     */
    protected $emetteur;

    /**
     * @ManyToOne(targetEntity="ifacebook\model\Utilisateur\Utilisateur")
     * @JoinColumn(nullable=false, name="destinataire", referencedColumnName="id")
     * @var Utilisateur $destinataire
     */
    protected $destinataire;

    /**
     * @OneToOne(targetEntity="ifacebook\model\Utilisateur\Utilisateur")
     * @JoinColumn(nullable=true, name="parent", referencedColumnName="id")
     * @var Utilisateur $parent
     */
    protected $parent;

    /**
     * @Column(type="integer")
     * @var int $aime
     */
    protected $aime;

    use JsonSerializeTrait;

    /**
     * @return mixed
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * @param mixed $emetteur
     *
     * @return Message
     */
    public function setEmetteur($emetteur)
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * @param mixed $destinataire
     *
     * @return Message
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     *
     * @return Message
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return int
     */
    public function getAime()
    {
        return null === $this->aime ? 0 : $this->aime;
    }

    /**
     * @param int $aime
     *
     * @return Message
     */
    public function setAime($aime)
    {
        $this->aime = $aime;

        return $this;
    }
}
