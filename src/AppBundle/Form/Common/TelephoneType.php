<?php

namespace AppBundle\Form\Common;

use Symfony\Component\OptionsResolver\OptionsResolver;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;

/**
 * Champs pour la saise d'un numéro de Téléphone.
 */
class TelephoneType extends PhoneNumberType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.telephone.form.label',
                // todo : lorsque placeholder, ce place sur la div avec le widget selection pays. a voir pourquoi
                'format' => PhoneNumberFormat::INTERNATIONAL,
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'preferred_country_choices' => ['FR'],
            ]
        );
    }
}
