<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use App\Model\Utilisateur\UtilisateurRepository;
use AppBundle\Twig\FormatExtension;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Widget de sélection des utilisateurs pour un concepteur bea (select2).
 */
class UtilisateurConcepteurBeaSelectType extends EntityType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var FormatExtension
     */
    protected $twigFormatExtension;

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param FormatExtension $twigFormatExtension
     */
    public function setTwigFormatExtension(FormatExtension $twigFormatExtension)
    {
        $this->twigFormatExtension = $twigFormatExtension;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'appbundle.utilisateurconcepteurselect.form.label',
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getFullname();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    /* @var UtilisateurRepository $entityRepository */

                    return $entityRepository->selectAllByRole(Role::CONCEPTEUR_BEA);
                },
                'choices_as_values' => true,
                'placeholder' => $this->translator->trans('form.concepteur.placeholder'),
                'attr' => [
                    'class' => 'wantSelect2',
                ],
            ]
        );
    }
}
