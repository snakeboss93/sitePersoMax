<?php

namespace AppBundle\Form\Agence;

use App\Model\Agence\Agence;
use AppBundle\Form\AdressePaysVille\AdressePaysVilleType;
use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Common\CouleurSelectType;
use AppBundle\Form\Common\EmailType;
use AppBundle\Form\Common\LocationType;
use AppBundle\Form\Common\NomInterneType;
use AppBundle\Form\Common\NomCommercialType;
use AppBundle\Form\Common\PrefixIntracommunautaireType;
use AppBundle\Form\Common\RefererType;
use AppBundle\Form\Common\SirenType;
use AppBundle\Form\Societe\SocieteSelectType;
use AppBundle\Form\Common\TelecopieType;
use AppBundle\Form\Common\TelephoneType;
use AppBundle\Form\Common\TrigrammeType;
use AppBundle\Form\Common\PositiveIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la création et l'édition des agences.
 */
class AgenceType extends AbstractCommonType
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
            ->add('societe', SocieteSelectType::class)
            ->add('nomInterne', NomInterneType::class)
            ->add('nomCommercial', NomCommercialType::class)
            ->add('adresse', AdressePaysVilleType::class)
            ->add('trigramme', TrigrammeType::class)
            ->add('location', LocationType::class)
            ->add('numeroTelephone', TelephoneType::class)
            ->add('numeroTelecopie', TelecopieType::class)
            ->add('email', EmailType::class)
            ->add('siren', SirenType::class)
            ->add('prefix', PrefixIntracommunautaireType::class)
            ->add('couleur', CouleurSelectType::class)
            ->add(
                'OS',
                PositiveIntegerType::class,
                [
                    'label' => 'appbundle.agence.form.os.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.agence.form.os.placeholder',
                    ],
                ]
            )
            ->add(
                'OC',
                PositiveIntegerType::class,
                [
                    'label' => 'appbundle.agence.form.oc.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.agence.form.oc.placeholder',
                    ],
                ]
            )
            ->add(
                'reception',
                PositiveIntegerType::class,
                [
                    'label' => 'appbundle.agence.form.reception.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.agence.form.reception.placeholder',
                    ],
                ]
            )
            ->add(
                'cloture',
                PositiveIntegerType::class,
                [
                    'label' => 'appbundle.agence.form.cloture.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.agence.form.cloture.placeholder',
                    ],
                ]
            )
            ->add('referer', RefererType::class);
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
