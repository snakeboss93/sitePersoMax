<?php

namespace AppBundle\Form\Common\Filtre\Dossier;

use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Dossier\Attribut\PotentielChoiceType;
use AppBundle\Form\Dossier\Attribut\StatusChoiceType;
use AppBundle\Form\Dossier\Attribut\NumeroDossierType;
use AppBundle\Form\Transformer\ContactsHiddenTransformer;
use AppBundle\Form\Transformer\VilleHiddenTransformer;
use AppBundle\Form\Utilisateur\UtilisateurCommercialSelectType;
use AppBundle\Form\Utilisateur\UtilisateurConducteurSelectType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la filtrer les dossier.
 */
abstract class AbstractFiltreDossier extends AbstractCommonType
{
    /**
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false,
                'validation_groups' => 'filter',
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'contact',
            HiddenType::class,
            [
                'required' => false,
                'attr' => ['class' => 'hidden'],
                'label_attr' => ['class' => 'hidden'],
            ]
        );
        $builder->get('contact')
            ->addModelTransformer(new ContactsHiddenTransformer($this->doctrine));

        $builder
            ->add('numero', NumeroDossierType::class)
            ->add(
                'villeConstruction',
                HiddenType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'hidden'],
                    'label_attr' => ['class' => 'hidden'],
                ]
            )->add('status', StatusChoiceType::class)
            ->add('potentiel', PotentielChoiceType::class)
            ->add('utilisateurAttribution', UtilisateurCommercialSelectType::class, ['required' => false])
            ->add(
                'utilisateurConducteur',
                UtilisateurConducteurSelectType::class,
                ['required' => false]
            )
        ;

        $builder->get('villeConstruction')
            ->addModelTransformer(new VilleHiddenTransformer($this->doctrine, true));
    }
}
