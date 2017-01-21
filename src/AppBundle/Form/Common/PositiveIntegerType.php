<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget pour les entiers positifs uniquement.
 *
 * Le vue du widget injecte systématiquement le paramètre min=0.
 */
class PositiveIntegerType extends IntegerType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positive_integer';
    }
}
