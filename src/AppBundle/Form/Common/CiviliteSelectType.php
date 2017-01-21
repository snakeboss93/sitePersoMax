<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la sélection de la civilité.
 */
class CiviliteSelectType extends AbstractCommonType
{
    // @todo voir comment la traduction peut etre prise en compte depuis le static
    public static $choices = [
        'Monsieur' => 'appbundle.civilite.form.choice.monsieur',
        'Madame' => 'appbundle.civilite.form.choice.madame',
    ];

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'appbundle.civilite.form.label',
                'choices' => [
                    'Monsieur' => 'appbundle.civilite.form.choice.monsieur',
                    'Madame' => 'appbundle.civilite.form.choice.madame',
                ],
                'placeholder' => 'appbundle.civilite.form.choice.vide',
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
