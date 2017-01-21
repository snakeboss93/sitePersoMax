<?php

namespace AppBundle\Form\Common;

use AppBundle\Form\Transformer\DateTimeFieldDataTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Champs pour le widget dateTimePicker.
 */
class DateTimePickerType extends AbstractCommonType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new DateTimeFieldDataTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'label' => 'appbundle.datetime.form.label',
                'attr' => [
                    'class' => 'dateTimePicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'dd-mm-YYYY HH:ii',
                ],
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return TextType::class;
    }
}
