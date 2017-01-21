<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saise d'un nom.
 */
class NomType extends TextType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            array(
                'label' => 'appbundle.nom.form.label',
                'attr' => ['placeholder' => 'appbundle.nom.form.placeholder'],
            )
        );
    }
}
