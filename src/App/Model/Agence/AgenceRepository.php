<?php

namespace App\Model\Agence;

use App\Doctrine\Utils\PagerFantaTools;
use App\Doctrine\Utils\TrigrammeGeneratorTrait;
use App\Model\Societe\Societe;
use App\Model\Societe\SocieteRepository;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;

/**
 * AgenceRepository.
 */
class AgenceRepository extends EntityRepository
{
    const MAX_PER_PAGE = 50;

    /**
     * @param int    $currentPage
     * @param string $sortField
     * @param string $sortOrder
     * @param int    $maxPerPage
     *
     * @return Pagerfanta|Agence[]
     */
    public function listeAvecPagination(
        $currentPage = 1,
        $sortField = 'id',
        $sortOrder = 'ASC',
        $maxPerPage = self::MAX_PER_PAGE
    ) {
        if ($sortField === 'societe') {
            $queryBuilder = $this
                ->createQueryBuilder('a')
                ->innerJoin('a.societe', 'societe')
                ->orderBy('societe.nom', $sortOrder);
        } else {
            $queryBuilder = $this->createQueryBuilder('a')->addOrderBy('a.'.$sortField, $sortOrder);
        }

        return PagerFantaTools::paginate($queryBuilder, $currentPage, $maxPerPage);
    }

    /**
     * Tableau d'agence dont la clé est le trigramme de l'agence.
     *
     * @return Agence[]
     */
    public function listeAgenceIndexeParTrigramme()
    {
        $datas = [];
        /** @var Agence[] $agences */
        $agences = $this->findAll();
        foreach ($agences as $agence) {
            $datas[$agence->getTrigramme()] = $agence;
        }

        return $datas;
    }

    /**
     * Liste des sociétés par agence.
     *
     * $choices[<societe_nom>][agence_id]
     *
     * @param SocieteRepository $societeRepository
     *
     * @return array|Agence[]
     */
    public function listeParNomSociete(SocieteRepository $societeRepository)
    {
        /** @var Societe[] $societes */
        $societes = $societeRepository->findAll();
        $choices = [];

        /** @var Societe $societe */
        foreach ($societes as $societe) {
            $choices[$societe->getNom()] = [];
            $agences = $societe->getAgence();
            /** @var Agence $agence */
            foreach ($agences as $agence) {
                $choices[$societe->getNom()][$agence->getId()] = $agence;
            }
        }

        return $choices;
    }

    /**
     * Liste des agences pour une société.
     *
     * @param string $societeTrigramme
     *
     * @return array|Agence[]
     */
    public function listeParSociete($societeTrigramme)
    {
        $societeRepository = $this->getEntityManager()->getRepository(Societe::class);
        $societe = $societeRepository->findOneBy(['trigramme' => $societeTrigramme]);
        $choices = [];

        $agences = $societe->getAgence();
        /** @var Agence $agence */
        foreach ($agences as $agence) {
            $choices[$agence->getId()] = $agence;
        }
        ksort($choices);

        return $choices;
    }

    /**
     * Retourne la liste des agences d'une société parmi une liste d'agences.
     *
     * Exemple d'utilisation : récupérer la liste d'agences d'une société qui sont affectées à un utilisateur.
     *
     * @param Societe $societe
     * @param array   $agencesTrigrammes
     *
     * @return array
     */
    public function fetchBySocieteAndTrigrammesAgences($societe, $agencesTrigrammes)
    {
        return $this->createQueryBuilder('a')
            ->where('a.societe = :societe')
            ->andWhere('a.trigramme IN (:agencesTrigrammes)')
            ->setParameter('societe', $societe)
            ->setParameter('agencesTrigrammes', array_keys($agencesTrigrammes))
            ->getQuery()
            ->getResult();
    }

    use TrigrammeGeneratorTrait;
}
