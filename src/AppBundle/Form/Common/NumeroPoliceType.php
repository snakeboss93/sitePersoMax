<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise d'un numéro de police d'assurance.
 */
class NumeroPoliceType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.numeropolice.form.label',
                'required' => false,
                'attr' => [
                    'placeholder' => 'appbundle.numeropolice.form.placeholder',
                ],
            ]
        );
    }
}
