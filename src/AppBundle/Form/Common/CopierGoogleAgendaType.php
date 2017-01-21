<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CopierGoogleAgendaType.
 */
class CopierGoogleAgendaType extends CheckboxType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'copier_google_agenda.form.label',
                'value' => '1',
                'required' => false,
                'translation_domain' => 'messages',
            ]
        );
    }
}
