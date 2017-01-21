<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use App\Model\Utilisateur\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Widget de sélection des utilisateurs pour un conducteur de travaux (select2).
 */
class UtilisateurConducteurSelectType extends EntityType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

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
                'label' => 'appbundle.utilisateurconducteurselect.form.label',
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getFullname();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    /* @var UtilisateurRepository $entityRepository */
                    return $entityRepository->selectAllByRole(Role::CONDUCTEUR_TRAVAUX);
                },
                'choices_as_values' => true,
                'placeholder' => $this->translator->trans('form.conducteur.placeholder'),
                'attr' => [
                    'class' => 'wantSelect2',
                ],
            ]
        );
    }
}
