<?php

namespace AppBundle\Form\Common;

use App\Doctrine\Types\EtapesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour la sélection des étapes.
 */
class EtapesSelectType extends AbstractCommonType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'appbundle.etapes.form.label',
                'choices' => EtapesType::getChoices(),
                'placeholder' => 'appbundle.etapes.form.choice.vide',
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
