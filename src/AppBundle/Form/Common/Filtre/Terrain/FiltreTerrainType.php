<?php

namespace AppBundle\Form\Common\Filtre\Terrain;

use AppBundle\Form\Agence\AgenceSelectType;
use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Transformer\VilleHiddenTransformer;
use AppBundle\Form\Utilisateur\UtilisateurCommercialSelectType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la filtrer les Terrains.
 */
class FiltreTerrainType extends AbstractCommonType
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
                'ville',
                HiddenType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'hidden'],
                    'label_attr' => ['class' => 'hidden'],
                ]
            )
            ->add('rayon', TextType::class, ['required' => false])
            ->add('prixMin', TextType::class, ['required' => false])
            ->add('prixMax', TextType::class, ['required' => false])
            ->add('superficieMin', TextType::class, ['required' => false])
            ->add('superficieMax', TextType::class, ['required' => false])
            ->add('commercial', UtilisateurCommercialSelectType::class, ['required' => false])
            ->add('agence', AgenceSelectType::class, ['required' => false]);

        $builder->get('ville')
            ->addModelTransformer(new VilleHiddenTransformer($this->doctrine, true));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'csrf_protection' => false,
                    'validation_groups' => 'filter',
                ]
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'filtreTerr';
    }
}
