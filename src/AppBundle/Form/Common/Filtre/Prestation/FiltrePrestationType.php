<?php

namespace AppBundle\Form\Common\Filtre\Prestation;

use AppBundle\Form\Common\AbstractCommonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour filtrer les Prestations.
 */
class FiltrePrestationType extends AbstractCommonType
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
            ->add('intitule', TextType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
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
        return 'filtrePrestation';
    }
}
