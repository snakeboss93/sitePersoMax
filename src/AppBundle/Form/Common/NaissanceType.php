<?php

namespace AppBundle\Form\Common;

use AppBundle\Form\Pays\PaysSelectType;
use AppBundle\Form\Transformer\VilleHiddenTransformer;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs de formulaire pour la saisie d'une date de naissance.
 */
class NaissanceType extends AbstractCommonType
{
    /**
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'dateNaissance',
                DatePickerType::class,
                ['required' => false, 'attr' => ['class' => 'datePickerDefaultVide']]
            )
            ->add('paysNaissance', PaysSelectType::class, ['attr' => ['class' => 'wantSelect2 AdressePaysVille']])
            ->add(
                'codePostalNaissance',
                TextType::class,
                [
                    'label' => 'label.saisiecp.libre',
                    'attr' => ['placeholder' => 'placeholder.saisiecp.libre', 'class' => 'villeNoFr cpLibre'],
                ]
            )
            ->add(
                'villeNaissance',
                HiddenType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'hidden'],
                    'label_attr' => ['class' => 'hidden'],
                ]
            )
            ->add(
                'villeNaissanceLibre',
                TextType::class,
                [
                    'label' => 'label.saisieville.libre',
                    'attr' => ['placeholder' => 'placeholder.saisieville.libre', 'class' => 'villeNoFr villeLibre'],
                ]
            );

        $builder->get('villeNaissance')
            ->addModelTransformer(new VilleHiddenTransformer($this->doctrine, true));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'inherit_data' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'naissance';
    }
}
