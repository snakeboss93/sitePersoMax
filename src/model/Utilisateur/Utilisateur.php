<?php

namespace ifacebook\model\Utilisateur;

use ifacebook\model\traits\IdTrait;
use ifacebook\model\traits\JsonSerializeTrait;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="fredouil.utilisateur")
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
     * @Column(type="string", length=45)
     * @var string $pass
     */
    protected $pass;

    /**
     * @Column(type="string", length=45)
     * @var string $nom
     */
    protected $nom;

    /**
     * @Column(type="string", length=45)
     * @var string $prenom
     */
    protected $prenom;

    /**
     * @Column(type="string", length=100)
     * @var string $statut
     */
    protected $statut;

    /**
     * @Column(type="string", length=200)
     * @var string $avatar
     */
    protected $avatar;

    /**
     * @Column(type="datetime")
     * @var \DateTime $date_de_naissance
     */
    protected $date_de_naissance;

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

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return strip_tags($this->statut);
    }

    /**
     * @param mixed $statut
     *
     * @return Utilisateur
     */
    public function setStatut($statut)
    {
        $this->statut = strip_tags($statut);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return null !== $this->avatar && '' !== $this->avatar ? $this->avatar : 'images/avatar/defaut.png';
    }

    /**
     * @param mixed $avatar
     *
     * @return Utilisateur
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDeNaissance()
    {
        return $this->date_de_naissance;
    }

    /**
     * @param mixed $dateDeNaissance
     *
     * @return Utilisateur
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->date_de_naissance = $dateDeNaissance;

        return $this;
    }

    /**
     * Permet de retourner un string avec le nom complet (nom + prenom).
     *
     * @return string
     */
    public function getFullName()
    {
        return null !== $this->getNom() && null !== $this->getPrenom() ? $this->getPrenom().' '.$this->getNom(
            ) : 'Erreur nom';
    }
}
