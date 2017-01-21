<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Utilisateur\Utilisateur;
use App\Model\Utilisateur\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Widget de sélection des utilisateurs (select2).
 */
class UtilisateurSelectType extends EntityType
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
                'label' => 'appbundle.utilisateurselect.form.label',
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getFullname();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    /* @var UtilisateurRepository $entityRepository */
                    return $entityRepository->selectAllActive();
                },
                'choices_as_values' => true,
                'placeholder' => $this->translator->trans('appbundle.utilisateurselect.form.placeholder'),
                'attr' => [
                    'class' => 'wantSelect2',
                ],
            ]
        );
    }
}
