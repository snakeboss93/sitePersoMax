<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Utilisateur\Utilisateur;
use App\Model\Utilisateur\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de sélection des utilisateurs qui ont un role de responsable commercial (select2).
 */
class UtilisateurResponsableCommerciauxSelectType extends EntityType
{
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
                    /* @var Utilisateur $utilisateur */
                    return $utilisateur->getFullname();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    /* @var UtilisateurRepository $entityRepository */
                    return $entityRepository->selectAllByRole('RESPONSABLE_COMMERCIAL');
                },
                'choices_as_values' => true,
                'attr' => [
                    'class' => 'wantSelect2',
                ],
            ]
        );
    }
}
