<?php

namespace AppBundle\Form\Common;

use App\Model\Agence\Agence;
use App\Model\Utilisateur\Utilisateur;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use App\Services\Media\MediaResolver;

/**
 * Champs pour le widget utilisateur agence.
 * Permet de générer un select qui contient à la fois l'identifiant utilisateur et l'identifiant agence.
 */
abstract class AbstractUtilisateurAgenceType extends ChoiceType
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var MediaResolver
     */
    protected $mediaResolver;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param MediaResolver $mediaResolver
     */
    public function setMediaResolver(MediaResolver $mediaResolver)
    {
        $this->mediaResolver = $mediaResolver;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Génère une liste d'utilisateur-agence en fonction de critères.
     * Elimine de la liste l'utilisateur courant si il lui est passé.
     *
     * @param array  $criterias
     * @param string $currentUserKey
     */
    public function setOptionsByCriterias($criterias, $currentUserKey)
    {
        $utilisateursRoleAttribution = $this->doctrine->getRepository(
            UtilisateurRoleAttribution::class
        )->sortedFindAllWithCriterias($criterias);

        foreach ($utilisateursRoleAttribution as $ura) {
            if (null !== $ura->getAgences() && null !== $ura->getAgences()[0] && null !== $ura->getUtilisateur()) {
                /** @var Utilisateur $u */
                $u = $ura->getUtilisateur();
                $key = $u->getId().'-'.$ura->getAgences()[0]->getId();
                $value = $u->getFullname().' - '.$ura->getAgences()[0]->getNomInterne().' ('.$ura->getAgences()[0]->getTrigramme().')';
                if ($currentUserKey !== $key) {
                    $this->options[$key] = $value;
                }
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.utilisateuragence.form.label',
                'choices' => $this->getOptions(),
                'attr' => [
                    'class' => 'utilisateurAgence wantSelect2',
                ],
                'choice_attr' => function ($val, $key, $index) {
                    list($utilisateurId, $agenceId) = explode('-', $val);

                    $utilisateur = $this->doctrine->getRepository(Utilisateur::class)->find($utilisateurId);
                    $agence = $this->doctrine->getRepository(Agence::class)->find($agenceId);

                    $userPictureThumb = $userPictureUrl = $utilisateur->getProfilePicturePath();
                    if (!is_null($userPictureUrl)) {
                        $userPictureThumb = $this->mediaResolver->resolve(
                            $utilisateur->getProfilePicturePath(),
                            'user_profile'
                        );
                    }

                    return [
                        'data-user-picture' => $userPictureThumb,
                        'data-user-name' => $utilisateur->getFullName(),
                        'data-agence-name' => $agence->getNomInterne().' ('.$agence->getTrigramme().')',
                    ];
                },
            ]
        );
    }

    /**
     * Le nom de notre champ.
     *
     * @return string
     */
    public function getName()
    {
        return 'utilisateur_agence_attribution';
    }

    /**
     * Construit le tableau des options.
     *
     * @return array
     */
    abstract public function getOptions();
}
