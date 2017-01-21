<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour le widget googleMap.
 */
class GoogleMapsUrlType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.googlemapsurl.label',
                'attr' => ['placeholder' => 'appbundle.googlemapsurl.placeholder'],
            ]
        );
    }
}
