<?php

namespace AppBundle\Form\Common\Filtre\Article;

use AppBundle\Form\Article\Attribut\LotArticleType;
use AppBundle\Form\Common\AbstractCommonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour filtrer les Articles.
 */
class FiltreArticleType extends AbstractCommonType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', TextType::class, ['label' => 'appbundle.intitule.form.label', 'required' => false])
            ->add(
                'lot',
                LotArticleType::class,
                [
                    'label' => 'appbundle.article.form.lot.label',
                    'required' => false,
                    'placeholder' => 'appbundle.article.form.lot.placeholder',
                ]
            )
        ;
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
        return 'filtreArticle';
    }
}
