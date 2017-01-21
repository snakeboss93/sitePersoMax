<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Model\Utilisateur\Utilisateur;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class UtilisateurBlameableTrait.
 */
trait BlameableTrait
{
    /**
     * @var Utilisateur
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Model\Utilisateur\Utilisateur")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var Utilisateur
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="App\Model\Utilisateur\Utilisateur")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @return Utilisateur
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param Utilisateur $createdBy
     *
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Utilisateur
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param Utilisateur $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
