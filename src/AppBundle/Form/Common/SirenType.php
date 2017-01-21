<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise d'un numéro de SIREN.
 */
class SirenType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.siren.form.label',
                'required' => false,
                'attr' => [
                    'placeholder' => 'appbundle.siren.form.placeholder',
                ],
            ]
        );
    }
}
