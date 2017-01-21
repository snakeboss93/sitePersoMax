<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise d'un nom Commercial.
 */
class NomCommercialType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'app.nomcommercial.label',
                'attr' => [
                    'placeholder' => 'app.nomcommercial.label',
                ],
            ]
        );
    }
}
