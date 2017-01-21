<?php

namespace AppBundle\Form\Utilisateur;

use AppBundle\Form\Common\AbstractCommonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserSearchType.
 */
class UtilisateurSearchType extends AbstractCommonType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite')
            ->add('nom')
            ->add('prenom')
            ->add('trigramme')
            ->add('numeroTelephone')
            ->add('numeroMobile')
            ->add('fonction')
            // Pour binder le form sur $.load()
            ->setMethod('GET');
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', 'App\Model\Utilisateur\Recherche\UtilisateurSearch');
    }
}
