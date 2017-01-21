<?php

namespace App\Doctrine\Traits;

use App\Model\Utilisateur\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CommentaireTrait.
 */
trait CommentaireTrait
{
    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Assert\Length(max=65535, groups={"Default", "create", "edit", "import", "light"})
     */
    protected $commentaire;

    /**
     *  Auteur du commentaire.
     *
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity="App\Model\Utilisateur\Utilisateur")
     * @ORM\JoinColumn(name="auteur_id", referencedColumnName="id")
     */
    protected $auteur;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datetime;

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     *
     * @return $this
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Utilisateur
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param Utilisateur $auteur
     *
     * @return $this
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return $this
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }
}
