<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NomTrait.
 */
trait NomTrait
{
    /**
     * Nom.
     *
     * @var string
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="app.nom.nom.assert_notblank", groups={"Default", "create", "edit", "import", "light"})
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $nom;

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return $this
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }
}
