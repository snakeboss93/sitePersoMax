<?php

namespace App\Model\Agence;

use App\Doctrine\Traits\AdressePaysVilleTrait;
use App\Doctrine\Traits\EmailTrait;
use App\Doctrine\Traits\IdTrait;
use App\Doctrine\Traits\LocationTrait;
use App\Doctrine\Traits\NomInterneCommercialTrait;
use App\Doctrine\Traits\NumeroTelecopieTrait;
use App\Doctrine\Traits\NumeroTelephoneTrait;
use App\Doctrine\Traits\PrefixIntracommunautaireTrait;
use App\Doctrine\Traits\SirenTrait;
use App\Doctrine\Traits\TrigrammeTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Model\Societe\Societe;
use Gedmo\Mapping\Annotation as Gedmo;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Class Agence.
 *
 * @ORM\Entity(repositoryClass="App\Model\Agence\AgenceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity(fields={"trigramme"}, message="app.agence.trigramme.uniqueentity", groups={"Default", "create", "edit", "import", "light"})
 */
class Agence
{
    const ACTION_CREER = 'AGENCE:CREER';
    const ACTION_MODIFIER = 'AGENCE:MODIFIER';
    const ACTION_DESACTIVER = 'AGENCE:DESACTIVER';
    const ACTION_LISTER = 'AGENCE:LISTER';

    use IdTrait;

    /**
     * Société d'une agence.
     *
     * @var Societe
     * @ORM\ManyToOne(targetEntity="App\Model\Societe\Societe", inversedBy="agence", fetch="EAGER")
     * @ORM\JoinColumn(name="societe_id", referencedColumnName="id")
     * @Assert\NotBlank(message="app.agence.societe.notblank", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $societe;

    use NomInterneCommercialTrait;

    use AdressePaysVilleTrait;

    use TrigrammeTrait;

    use LocationTrait;

    use NumeroTelephoneTrait;

    use NumeroTelecopieTrait;

    use EmailTrait;

    use SirenTrait;

    use PrefixIntracommunautaireTrait;

    /**
     * OS d'une agence. (En nombre de semaines).
     *
     * @var int
     * @ORM\Column(name="os", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $OS;

    /**
     * OC d'une agence. (En nombre de semaines).
     *
     * @var int
     * @ORM\Column(name="oc", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $OC;

    /**
     * Reception Chantier d'une agence. (En nombre de semaines).
     *
     * @var int
     * @ORM\Column(name="reception", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $reception;

    /**
     * Cloture Chantier d'une agence. (En nombre de semaines).
     *
     * @var int
     * @ORM\Column(name="cloture", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $cloture;

    /**
     * Couleur.
     *
     * @var string
     * @ORM\Column(name="couleur", type="string", nullable=true)
     */
    protected $couleur;

    use SoftDeleteableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Ouvrage\Ouvrage", inversedBy="agence")
     * @ORM\JoinColumn(name="ouvrage_id", referencedColumnName="id")
     */
    protected $ouvrage;

    /**
     * @var float
     * @ORM\Column(name="alea", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $alea;

    /**
     * @var float
     * @ORM\Column(name="assurance", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $assurance;

    /**
     * @var float
     * @ORM\Column(name="provision_pour_risque", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $provisionPourRisque;

    /**
     * @var float
     * @ORM\Column(name="sav", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $sav;

    /**
     * @var float
     * @ORM\Column(name="ajustement_agence", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $ajustementAgence;

    /**
     * IndicePrixType.
     *
     * @var int
     * @ORM\Column(type="IndicePrixType", nullable=true)
     * @DoctrineAssert\Enum(entity="App\Doctrine\Types\IndicePrixType")
     */
    protected $indicePrixDevis;

    /**
     * IndicePrixType.
     *
     * @var int
     * @ORM\Column(type="IndicePrixType", nullable=true)
     * @DoctrineAssert\Enum(entity="App\Doctrine\Types\IndicePrixType")
     */
    protected $indicePrixDp;

    /**
     * Get oS.
     *
     * @return int
     */
    public function getOS()
    {
        return $this->OS;
    }

    /**
     * Set oS.
     *
     * @param int $oS
     *
     * @return Agence
     */
    public function setOS($oS)
    {
        $this->OS = $oS;

        return $this;
    }

    /**
     * Get oC.
     *
     * @return int
     */
    public function getOC()
    {
        return $this->OC;
    }

    /**
     * Set oP.
     *
     * @param int $oC
     *
     * @return Agence
     */
    public function setOC($oC)
    {
        $this->OC = $oC;

        return $this;
    }

    /**
     * Get reception.
     *
     * @return int
     */
    public function getReception()
    {
        return $this->reception;
    }

    /**
     * Set reception.
     *
     * @param int $reception
     *
     * @return Agence
     */
    public function setReception($reception)
    {
        $this->reception = $reception;

        return $this;
    }

    /**
     * Get cloture.
     *
     * @return int
     */
    public function getCloture()
    {
        return $this->cloture;
    }

    /**
     * Set cloture.
     *
     * @param int $cloture
     *
     * @return Agence
     */
    public function setCloture($cloture)
    {
        $this->cloture = $cloture;

        return $this;
    }

    /**
     * Get societe.
     *
     * @return Societe
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set societe.
     *
     * @param Societe $societe
     *
     * @return Agence
     */
    public function setSociete(Societe $societe = null)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Retourne la nom de l'agence avec le trigramme.
     *
     * @return string
     */
    public function getNomInterneAvecTrigramme()
    {
        return $this->getNomInterne().' ('.$this->getTrigramme().')';
    }

    /**
     * Retourne la nom de l'agence avec la societe si elle existe.
     *
     * @return string
     */
    public function getNomAvecNomSociete()
    {
        if (null === $this->getSociete()) {
            return $this->getNomInterne();
        } else {
            return $this->getNomInterne().' ('.$this->getSociete()->getNom().')';
        }
    }

    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param string $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * @return mixed
     */
    public function getOuvrage()
    {
        return $this->ouvrage;
    }

    /**
     * @param mixed $ouvrage
     *
     * @return Agence
     */
    public function setOuvrage($ouvrage)
    {
        $this->ouvrage = $ouvrage;

        return $this;
    }

    /**
     * @return int
     */
    public function getAlea()
    {
        return $this->alea;
    }

    /**
     * @param int $alea
     *
     * @return Agence
     */
    public function setAlea($alea)
    {
        $this->alea = $alea;

        return $this;
    }

    /**
     * @return int
     */
    public function getAssurance()
    {
        return $this->assurance;
    }

    /**
     * @param int $assurance
     *
     * @return Agence
     */
    public function setAssurance($assurance)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * @return int
     */
    public function getProvisionPourRisque()
    {
        return $this->provisionPourRisque;
    }

    /**
     * @param int $provisionPourRisque
     *
     * @return Agence
     */
    public function setProvisionPourRisque($provisionPourRisque)
    {
        $this->provisionPourRisque = $provisionPourRisque;

        return $this;
    }

    /**
     * @return int
     */
    public function getSav()
    {
        return $this->sav;
    }

    /**
     * @param int $sav
     *
     * @return Agence
     */
    public function setSav($sav)
    {
        $this->sav = $sav;

        return $this;
    }

    /**
     * @return int
     */
    public function getAjustementAgence()
    {
        return $this->ajustementAgence;
    }

    /**
     * @param int $ajustementAgence
     *
     * @return Agence
     */
    public function setAjustementAgence($ajustementAgence)
    {
        $this->ajustementAgence = $ajustementAgence;

        return $this;
    }

    /**
     * @return int
     */
    public function getIndicePrixDevis()
    {
        return $this->indicePrixDevis;
    }

    /**
     * @param int $indicePrixDevis
     *
     * @return Agence
     */
    public function setIndicePrixDevis($indicePrixDevis)
    {
        $this->indicePrixDevis = $indicePrixDevis;

        return $this;
    }

    /**
     * @return int
     */
    public function getIndicePrixDp()
    {
        return $this->indicePrixDp;
    }

    /**
     * @param int $indicePrixDp
     *
     * @return Agence
     */
    public function setIndicePrixDp($indicePrixDp)
    {
        $this->indicePrixDp = $indicePrixDp;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nomInterne;
    }
}
