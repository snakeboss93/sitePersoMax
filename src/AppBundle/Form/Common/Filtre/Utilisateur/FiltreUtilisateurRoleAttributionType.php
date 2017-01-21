<?php

namespace AppBundle\Form\Common\Filtre\Utilisateur;

use AppBundle\Form\Agence\AgenceSelectType;
use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Role\RoleSelectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UtilisateurRoleAttributionType.
 */
class FiltreUtilisateurRoleAttributionType extends AbstractCommonType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'role',
                RoleSelectType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'wantSelect2'],
                ]
            )
            ->add(
                'agences',
                AgenceSelectType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'wantSelect2'],
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'validation_groups' => 'filter',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'filtreURA';
    }
}
