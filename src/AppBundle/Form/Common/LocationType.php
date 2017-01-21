<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saisie de la latitude et longitude d'un point sur une google Map.
 */
class LocationType extends AbstractCommonType
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
            ->add(
                'lat',
                TextType::class,
                [
                    'label' => 'app.latitude.label',
                    'attr' => ['placeholder' => 'app.latitude.placeholder'],
                ]
            )
            ->add(
                'lng',
                TextType::class,
                [
                    'label' => 'app.longitude.label',
                    'attr' => ['placeholder' => 'app.longitude.placeholder'],
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
        return 'gmap';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'required' => false,
                'label' => 'app.location.label',
                'data_class' => 'App\Doctrine\ValueObject\Location',
            ]
        );
    }
}
