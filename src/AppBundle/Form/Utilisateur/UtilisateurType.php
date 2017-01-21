<?php

namespace AppBundle\Form\Utilisateur;

use AppBundle\Form\Common\AbstractCommonType;
use AppBundle\Form\Common\CiviliteSelectType;
use AppBundle\Form\Common\EmailType;
use AppBundle\Form\Common\NomType;
use AppBundle\Form\Common\PrenomType;
use AppBundle\Form\Common\RefererType;
use AppBundle\Form\Common\TelephoneType;
use AppBundle\Form\Common\TrigrammeType;
use AppBundle\Form\Common\EnableType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la création et l'édition d'utilisateur.
 */
class UtilisateurType extends AbstractCommonType
{
    /**
     * @var string
     */
    private $mode;

    /**
     * UtilisateurType constructor.
     *
     * @param string $mode
     */
    public function __construct($mode = 'create')
    {
        $this->mode = $mode;
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
            ->add('civilite', CiviliteSelectType::class)
            ->add('nom', NomType::class)
            ->add('prenom', PrenomType::class)
            ->add('email', EmailType::class)
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'appbundle.utilisateur.form.plainPassword.label',
                    'required' => $this->mode === 'create',
                    'attr' => [
                        'placeholder' => 'appbundle.utilisateur.form.plainPassword.placeholder',
                    ],
                ]
            )
            ->add(
                'confirmPassword',
                PasswordType::class,
                [
                    'label' => 'appbundle.utilisateur.form.confirmPassword.label',
                    'required' => $this->mode === 'create',
                    'attr' => [
                        'placeholder' => 'appbundle.utilisateur.form.confirmPassword.placeholder',
                    ],
                ]
            )
            ->add('trigramme', TrigrammeType::class)
            ->add('numeroTelephone', TelephoneType::class)
            ->add(
                'numeroMobile',
                TelephoneType::class,
                [
                    'label' => 'appbundle.utilisateur.form.numeroMobile.label',
                    'required' => false,
                    // 'attr' => ['placeholder' => 'appbundle.utilisateur.form.numeroMobile.placeholder'],
                    // todo : cf telephoneType
                ]
            )
            ->add(
                'fonction',
                TextType::class,
                [
                    'label' => 'appbundle.utilisateur.form.fonction.label',
                    'attr' => [
                        'placeholder' => 'appbundle.utilisateur.form.fonction.placeholder',
                    ],
                ]
            )
            ->add(
                'raccourciTelephone',
                TextType::class,
                [
                    'label' => 'appbundle.utilisateur.form.raccourciTelephone.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.utilisateur.form.raccourciTelephone.placeholder',
                    ],
                ]
            )
            ->add(
                'raccourciMobile',
                TextType::class,
                [
                    'label' => 'appbundle.utilisateur.form.raccourciMobile.label',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'appbundle.utilisateur.form.raccourciMobile.placeholder',
                    ],
                ]
            )
            ->add(
                'profilePictureFile',
                FileType::class,
                [
                    'label' => 'appbundle.utilisateur.form.profilePictureFile.label',
                    'required' => false,
                ]
            )
            ->add('referer', RefererType::class)
            ->add('enabled', EnableType::class);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\Model\Utilisateur\Utilisateur',
                'validation_groups' => $this->mode,
            ]
        );
    }
}
