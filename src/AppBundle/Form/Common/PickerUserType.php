<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Champs pour le widget PickerUser.
 */
class PickerUserType extends AbstractCommonType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'related_users',
            ChoiceType::class,
            [
                'label' => 'appbundle.pickeruser.form.label',
                'multiple' => 'multiple',
            ]
        )
            ->add('searchButton', ButtonType::class, []);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'pickerUser';
    }
}
