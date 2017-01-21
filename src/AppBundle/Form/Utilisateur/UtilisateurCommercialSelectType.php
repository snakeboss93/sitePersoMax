<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Agence\Agence;
use App\Model\Utilisateur\Utilisateur;
use App\Model\Role\Role;
use App\Model\Utilisateur\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Widget de sélection des utilisateurs qui as pour role commercial (select2).
 */
class UtilisateurCommercialSelectType extends EntityType
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'appbundle.utilisateurcommercialselect.form.label',
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getFullname();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    /** @var Agence $agenceSession */
                    $agenceSession = $this->session->get('agence');

                    //Attention si pas d'agence on retourne la liste toutes agences confondues
                    if (null === $agenceSession) {
                        /* @var UtilisateurRepository $entityRepository */

                        return $entityRepository->selectAllByRole(Role::COMMERCIAL);
                    }

                    /* @var UtilisateurRepository $entityRepository */
                    return $entityRepository->selectAllByRoleAndAgence(Role::COMMERCIAL, $agenceSession->getId());
                },
                'choices_as_values' => true,
                'placeholder' => $this->translator->trans('form.commercial.placeholder'),
                'attr' => [
                    'class' => 'wantSelect2',
                ],
            ]
        );
    }
}
