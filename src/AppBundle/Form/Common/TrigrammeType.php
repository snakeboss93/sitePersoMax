<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise d'un trigramme.
 * (Longueur maximal : 3 caracteres).
 */
class TrigrammeType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.trigramme.form.label',
                'attr' => [
                    'placeholder' => 'appbundle.trigramme.form.placeholder',
                    'maxlength' => '3',
                ],
            ]
        );
    }
}
