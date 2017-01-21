<?php

namespace AppBundle\Form\Common;

use AppBundle\Form\Transformer\DateFieldDataTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Champs pour le widget datePicker.
 */
class DatePickerType extends AbstractCommonType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.date.form.label',
                'attr' => [
                    'class' => 'datePicker',
                ],
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new DateFieldDataTransformer());
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return TextType::class;
    }
}
