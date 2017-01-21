<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saisie des adresses.
 */
class AdresseType extends TextareaType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.adresse.form.label',
                'attr' => [
                    'placeholder' => 'appbundle.adresse.form.placeholder',
                ],
            ]
        );
    }
}
