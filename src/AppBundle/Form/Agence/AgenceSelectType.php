<?php

namespace AppBundle\Form\Agence;

use App\Model\Agence\Agence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Widget de sélection des agences.
 */
class AgenceSelectType extends EntityType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'appbundle.agence.form.label',
                'class' => 'App\Model\Agence\Agence',
                'property' => 'nomInterne',
                'choice_label' => function (Agence $agence) {
                    return $agence->getNomAvecNomSociete();
                },
                'placeholder' => $this->translator->trans('appbundle.agence.form.placeholder'),
                'choices_as_values' => true,
            ]
        );
    }
}
