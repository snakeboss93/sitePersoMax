<?php

namespace App\Model\Utilisateur;

use App\Doctrine\Traits\IdentificationTrait;
use App\Doctrine\Traits\IdTrait;
use App\Doctrine\Traits\NumeroTelephoneTrait;
use App\Doctrine\Traits\RemoteTrait;
use App\Doctrine\Traits\TrigrammeTrait;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as FOSBaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

/**
 * Entité représentant les Utilisateur.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 *
 * @ORM\Entity(repositoryClass="App\Model\Utilisateur\UtilisateurRepository")
 * @ORM\EntityListeners({"App\Model\Utilisateur\UtilisateurListener"})
 * @UniqueEntity(fields={"email"}, message="app.utilisateur.email.uniqueentity", groups={"Default", "create", "edit", "import", "light"})
 * @UniqueEntity(fields={"username"}, message="app.utilisateur.username.uniqueentity", groups={"Default", "create", "edit", "import", "light"})
 * @UniqueEntity(fields={"trigramme"}, message="app.utilisateur.trigramme.uniqueentity", groups={"Default", "create", "edit", "import", "light"})
 * @Assert\Callback({"App\Model\Utilisateur\UtilisateurValidator", "validate"}, groups={"Default", "create", "edit", "import", "light"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Utilisateur extends FOSBaseUser implements \JsonSerializable
{
    const ACTION_CREER = 'UTILISATEUR:CREER';
    const ACTION_MODIFIER = 'UTILISATEUR:MODIFIER';
    const ACTION_DESACTIVER = 'UTILISATEUR:DESACTIVER';
    const ACTION_LISTER = 'UTILISATEUR:LISTER';
    const ACTION_AFFICHER = 'UTILISATEUR:AFFICHER';
    const ACTION_ROLE_ASSIGNER = 'UTILISATEUR:ROLE:ASSIGNER';

    /**
     * Identifiant unique de l'utilisateur.
     * Impossible d'utiliser IdTrait.
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use IdentificationTrait;

    use TrigrammeTrait;

    use NumeroTelephoneTrait;

    /**
     * Numéro mobile de l'utilisateur.
     *
     * @var PhoneNumber
     * @ORM\Column(name="numero_mobile", type="phone_number", nullable=true)
     * @AssertPhoneNumber(type="mobile")
     */
    protected $numeroMobile;

    /**
     * Fonction de l'utilisateur.
     *
     * @var string
     * @ORM\Column(name="fonction", type="string", length=255)
     * @Assert\NotBlank(message="app.utilisateur.fonction.assert_notblank", groups={"Default", "create", "edit", "light"})
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $fonction;

    /**
     * Transient pour le mot de passe de confirmation.
     *
     * @var string
     */
    protected $confirmPassword;

    /**
     * Overriding de la propriété pour rajouter des contrôles supplémentaires.
     *
     * @var string
     * @Assert\NotBlank(message="app.utilisateur.plainpassword.assert_notblank", groups={"create"})
     */
    protected $plainPassword;

    /**
     * Image de profil de l'utilisateur.
     *
     * @var UploadedFile
     *
     * @Assert\File(groups={"Default", "create", "edit", "import", "light"}, maxSize="20000000")
     * @Assert\Image(mimeTypesMessage="app.utilisateur.profilepicturefile.assert_image", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $profilePictureFile;

    /**
     * Chemin vers l'image de profil de l'utilisateur.
     *
     * @var string
     * @ORM\Column(name="image_path", type="string", length=255, nullable=true)
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $profilePicturePath;

    /**
     * Raccourci vers le téléphone de l'utilisateur (numéro interne?).
     *
     * @var string
     * @ORM\Column(name="raccourci_telephone", type="string", length=15, nullable=true)
     * @Assert\Length(max="15", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $raccourciTelephone;

    /**
     * Raccourci vers le mobile de l'utilisateur.
     *
     * @var string
     * @ORM\Column(name="raccourci_mobile", type="string", length=15, nullable=true)
     * @Assert\Length(max="15", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $raccourciMobile;

    /**
     * @var string
     * @Assert\Email(message="app.utilisateur.email.assert_email", groups={"Default", "create", "edit", "import", "light"})
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $email;

    /**
     * Identifiant fourni par Google Connect.
     *
     * @var string
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    protected $googleId;

    /**
     * @var string
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    use RemoteTrait;

    use SoftDeleteableEntity;

    /**
     * @var UtilisateurRoleAttribution[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution", mappedBy="utilisateur", fetch="EAGER")
     */
    protected $utilisateurRoleAttribution;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PhoneNumber
     */
    public function getNumeroMobile()
    {
        return $this->numeroMobile;
    }

    /**
     * Setter permettant de mettre soit un string soit un PhoneNumber.
     *
     * @param string|PhoneNumber $numeroMobile
     *
     * @return Utilisateur
     */
    public function setNumeroMobile($numeroMobile)
    {
        if (is_string($numeroMobile)) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $numeroMobile = str_replace(' ', '', $numeroMobile);
            $numeroMobile = str_replace('-', '', $numeroMobile);
            $phoneNumber = $phoneNumberUtil->parse(PhoneNumberUtil::normalize($numeroMobile), 'FR', null, true);
            $this->numeroMobile = $phoneNumber;
        } elseif (is_a($numeroMobile, 'libphonenumber\\PhoneNumber')) {
            $this->numeroMobile = $numeroMobile;
        }

        return $this;
    }

    /**
     * Retourne un string du numero de telephone humainement lisible.
     *
     * @return string
     */
    public function getNumeroMobileString()
    {
        if (null === $this->getNumeroMobile()) {
            return;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        if (33 === $this->getNumeroMobile()->getCountryCode()) {
            return $phoneNumberUtil->formatNationalNumberWithCarrierCode($this->getNumeroMobile(), 'NATIONAL');
        } else {
            return $phoneNumberUtil->format($this->getNumeroMobile(), 'INTERNATIONAL');
        }
    }

    /**
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * @param string $fonction
     *
     * @return Utilisateur
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * @param string $confirmPassword
     *
     * @return Utilisateur
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return array data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return UploadedFile
     */
    public function getProfilePictureFile()
    {
        return $this->profilePictureFile;
    }

    /**
     * @param UploadedFile $profilePictureFile
     *
     * @return Utilisateur
     */
    public function setProfilePictureFile(UploadedFile $profilePictureFile)
    {
        $this->profilePictureFile = $profilePictureFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * @param string $profilePicturePath
     *
     * @return Utilisateur
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getRaccourciTelephone()
    {
        return $this->raccourciTelephone;
    }

    /**
     * @param string $raccourciTelephone
     *
     * @return Utilisateur
     */
    public function setRaccourciTelephone($raccourciTelephone)
    {
        $this->raccourciTelephone = $raccourciTelephone;

        return $this;
    }

    /**
     * @return string
     */
    public function getRaccourciMobile()
    {
        return $this->raccourciMobile;
    }

    /**
     * @param string $raccourciMobile
     *
     * @return Utilisateur
     */
    public function setRaccourciMobile($raccourciMobile)
    {
        $this->raccourciMobile = $raccourciMobile;

        return $this;
    }

    /**
     * Prénom Nom (Trigramme).
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->getPrenom().' '.$this->getNom().' ('.$this->getTrigramme().')';
    }

    /**
     * Prénom Nom.
     *
     * @return string
     */
    public function getPrenomNom()
    {
        return $this->getPrenom().' '.$this->getNom();
    }

    /**
     * Civilité Prénom Nom.
     *
     * @return string
     */
    public function getCivilitePrenomNom()
    {
        return $this->getCivilite().' '.$this->getPrenomNom();
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     *
     * @return Utilisateur
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get google access token.
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * Set google access token.
     *
     * @param string $gat Google access token
     *
     * @return Utilisateur
     */
    public function setGoogleAccessToken($gat = null)
    {
        $this->googleAccessToken = $gat;

        return $this;
    }

    /**
     * @return UtilisateurRoleAttribution[]|ArrayCollection
     */
    public function getUtilisateurRoleAttribution()
    {
        return $this->utilisateurRoleAttribution;
    }

    /**
     * @param UtilisateurRoleAttribution[]|ArrayCollection $utilisateurRoleAttribution
     *
     * @return $this
     */
    public function setUtilisateurRoleAttribution($utilisateurRoleAttribution)
    {
        $this->utilisateurRoleAttribution = $utilisateurRoleAttribution;

        return $this;
    }

    /**
     * @param UtilisateurRoleAttribution $utilisateurRoleAttribution
     *
     * @return $this
     */
    public function addUtilisateurRoleAttribution(UtilisateurRoleAttribution $utilisateurRoleAttribution)
    {
        $this->utilisateurRoleAttribution[] = $utilisateurRoleAttribution;

        return $this;
    }
}
