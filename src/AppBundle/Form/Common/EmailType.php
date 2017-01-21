<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\EmailType as CoreEmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la saisie de l'email.
 */
class EmailType extends CoreEmailType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.email.form.label',
                'required' => false,
                'attr' => [
                    'placeholder' => 'appbundle.email.form.placeholder',
                    'pattern' => '(?!(^[.-].*|[^@]*[.-]@|.*\.{2,}.*)|^.{254}.)([a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~.-]+@)(?!-.*|.*-\.)([a-zA-Z0-9-]{1,63}\.)+[a-zA-Z]{2,15}',
                ],
            ]
        );
    }
}
