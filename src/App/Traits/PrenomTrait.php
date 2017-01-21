<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PrenomTrait.
 */
trait PrenomTrait
{
    /**
     * Nom.
     *
     * @var string
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(groups={"Default", "create", "edit", "import", "light"})
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $prenom;

    /**
     * @param string $prenom
     *
     * @return $this
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
}
