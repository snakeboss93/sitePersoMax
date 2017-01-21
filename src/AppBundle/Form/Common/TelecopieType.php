<?php

namespace AppBundle\Form\Common;

use Symfony\Component\OptionsResolver\OptionsResolver;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;

/**
 * Champs pour la saise d'un numéro de Télécopie.
 */
class TelecopieType extends PhoneNumberType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.telecopie.form.label',
                'format' => PhoneNumberFormat::NATIONAL,
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'preferred_country_choices' => ['FR'],
                'required' => false,
            ]
        );
    }
}
