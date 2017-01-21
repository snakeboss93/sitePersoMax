<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise du prefix intracommunautaire.
 */
class PrefixIntracommunautaireType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.prefixintracommunautaire.form.label',
                'required' => false,
                'attr' => [
                    'placeholder' => 'appbundle.prefixintracommunautaire.form.placeholder',
                ],
            ]
        );
    }
}
