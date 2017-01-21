<?php

namespace AppBundle\Form\Common\Filtre\Annonce;

use AppBundle\Form\Agence\AgenceSelectType;
use AppBundle\Form\Annonce\AnnonceSiteType;
use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Utilisateur\UtilisateurSelectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour filtrer les annonces.
 */
class FiltreTerrainType extends AbstractCommonType
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
            ->add('site', AnnonceSiteType::class, ['required' => false])
            ->add('agenceAttribuer', AgenceSelectType::class, ['required' => false])
            ->add('utilisateurAttribution', UtilisateurSelectType::class, ['required' => false])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => 'filter',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'filtreAnnonceAd';
    }
}
