<?php

namespace AppBundle\Form\Agence;

use App\Doctrine\Types\IndicePrixType;
use App\Model\Agence\Agence;
use AppBundle\Form\Common\AbstractCommonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AgenceParametreType.
 */
class AgenceParametreType extends AbstractCommonType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'alea',
                NumberType::class,
                [
                    'label' => 'appbundle.societe.form.alea.label',
                    'required' => true,
                    'scale' => 2,
                    'rounding_mode' => 2,
                ]
            )
            ->add(
                'assurance',
                NumberType::class,
                [
                    'label' => 'appbundle.societe.form.assurance.label',
                    'required' => true,
                    'scale' => 2,
                    'rounding_mode' => 2,
                ]
            )
            ->add(
                'provisionPourRisque',
                NumberType::class,
                [
                    'label' => 'appbundle.societe.form.provisionPourRisque.label',
                    'required' => true,
                    'scale' => 2,
                    'rounding_mode' => 2,
                ]
            )
            ->add(
                'sav',
                NumberType::class,
                [
                    'label' => 'appbundle.societe.form.sav.label',
                    'required' => true,
                    'scale' => 2,
                    'rounding_mode' => 2,
                ]
            )
            ->add(
                'ajustementAgence',
                NumberType::class,
                [
                    'label' => 'appbundle.societe.form.ajustementAgence.label',
                    'required' => true,
                    'scale' => 2,
                    'rounding_mode' => 2,
                ]
            )
            ->add(
                'indicePrixDevis',
                ChoiceType::class,
                [
                    'label' => 'appbundle.societe.form.indicePrixDevis.label',
                    'choices' => IndicePrixType::getChoices(),
                    'expanded' => true,
                    'multiple' => false,
                    'data' => 10,
                ]
            )
            ->add(
                'indicePrixDp',
                ChoiceType::class,
                [
                    'label' => 'appbundle.societe.form.indicePrixDp.label',
                    'choices' => IndicePrixType::getChoices(),
                    'expanded' => true,
                    'multiple' => false,
                    'data' => 10,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Agence::class]);
    }
}
