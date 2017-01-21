<?php

namespace App\Model\Utilisateur;

use App\Services\Uploader\Uploader;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UtilisateurManager.
 */
class UtilisateurManager implements UtilisateurManagerInterface
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var Uploader
     */
    protected $uploader;

    /**
     * Path vers le dossier d'upload des photos de profiles.
     *
     * @var string
     */
    protected $uploadProfilePictureDir;

    /**
     * UtilisateurManager constructor.
     *
     * @param UserManager        $userManager
     * @param ValidatorInterface $validator
     * @param Uploader           $uploader
     * @param string             $uploadProfilePictureDir
     */
    public function __construct(UserManager $userManager, ValidatorInterface $validator, Uploader $uploader, $uploadProfilePictureDir)
    {
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->uploader = $uploader;
        $this->uploadProfilePictureDir = $uploadProfilePictureDir;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Utilisateur $utilisateur)
    {
        if (!is_null($utilisateur->getProfilePictureFile())) {
            $this->uploadProfilePicture($utilisateur);
        }
        $this->userManager->updateUser($utilisateur);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function softDelete(Utilisateur $utilisateur)
    {
        $this->desactiver($utilisateur);
        $this->userManager->deleteUser($utilisateur);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Utilisateur $utilisateur)
    {
        $this->softDelete($utilisateur);
        $this->userManager->deleteUser($utilisateur);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Utilisateur $utilisateur)
    {
        if (!is_null($utilisateur->getProfilePictureFile())) {
            $this->uploadProfilePicture($utilisateur);
        }
        $this->userManager->updateUser($utilisateur);
    }

    /**
     * Desactiver un utilisateur.
     *
     * @param Utilisateur $utilisateur
     */
    public function desactiver(Utilisateur $utilisateur)
    {
        $utilisateur->setEnabled(false);
        $this->update($utilisateur);
    }

    /**
     * Activer un utilisateur.
     *
     * @param Utilisateur $utilisateur
     */
    public function activer(Utilisateur $utilisateur)
    {
        $utilisateur->setEnabled(true);
        $this->update($utilisateur);
    }

    /**
     * Persistence de l'image de profil de l'utilisateur.
     *
     * @param Utilisateur $utilisateur
     */
    protected function uploadProfilePicture(Utilisateur &$utilisateur)
    {
        $pictureFile = $utilisateur->getProfilePictureFile();
        $persistedPath = $this->uploader->upload($pictureFile, $this->uploadProfilePictureDir);

        $oldPath = $utilisateur->getProfilePicturePath();

        if (!is_null($oldPath)) {
            $this->uploader->remove($oldPath);
        }

        $utilisateur->setProfilePicturePath($persistedPath);
    }
}
