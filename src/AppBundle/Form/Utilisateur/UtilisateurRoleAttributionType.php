<?php

namespace AppBundle\Form\Utilisateur;

use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use AppBundle\Form\Common\AbstractCommonType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Agence\AgenceParSocieteType;

/**
 * Class UtilisateurRoleAttributionType.
 */
class UtilisateurRoleAttributionType extends AbstractCommonType
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * UtilisateurRoleAttributionType constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'utilisateur',
                EntityType::class,
                [
                    'class' => Utilisateur::class,
                    'choice_label' => function ($utilisateur) {
                        /* @var Utilisateur $utilisateur */
                        return $utilisateur->getFullname();
                    },
                    'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository->createQueryBuilder('u')->orderBy('u.prenom');
                    },
                ]
            )
            ->add(
                'role',
                EntityType::class,
                [
                    'class' => Role::class,
                    'choice_label' => 'intitule',
                    'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository->createQueryBuilder('r')->orderBy('r.intitule');
                    },
                ]
            )
            ->add('agences', AgenceParSocieteType::class)
            ->add('pointsDeVentes', HiddenType::class)
            ->setMethod('POST');

        $builder->get('pointsDeVentes')->addModelTransformer(
            new CallbackTransformer(
                function ($originalDescription) {
                    if (is_null($originalDescription)) {
                        return;
                    }

                    $pointsDeVenteIds = [];
                    foreach ($originalDescription->toArray() as $pointDeVente) {
                        $pointsDeVenteIds[] = (string) $pointDeVente->getId();
                    }

                    return \json_encode($pointsDeVenteIds);
                },
                function ($submittedDescription) {
                    $decodedDescription = \json_decode($submittedDescription);
                    $pointDeVenteRepository = $this->registry->getRepository('App:Model\PointDeVente\PointDeVente');
                    if (count($decodedDescription) === 0) {
                        return;
                    }
                    $pointsDeVente = [];
                    foreach ($decodedDescription as $idPointDeVente) {
                        $pointsDeVente[] = $pointDeVenteRepository->find($idPointDeVente);
                    }

                    return $pointsDeVente;
                }
            )
        );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => UtilisateurRoleAttribution::class]);
    }
}
