<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la configuration de l'activation d'un checkbox.
 */
class EnableType extends CheckboxType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.enable.form.label',
                'value' => '1',
                'required' => false,
            ]
        );
    }
}
