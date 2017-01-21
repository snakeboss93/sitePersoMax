<?php

namespace App\Model\Utilisateur;

use App\Doctrine\Utils\PagerFantaTools;
use App\Doctrine\Utils\TrigrammeGeneratorTrait;
use App\Model\Agence\Agence;
use App\Model\Role\Role;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;

/**
 * Class UtilisateurRepository.
 */
class UtilisateurRepository extends EntityRepository
{
    const MAX_PER_PAGE = 50;

    /**
     * @param int    $currentPage
     * @param string $sortField
     * @param string $sortOrder
     * @param int    $maxPerPage
     *
     * @return Pagerfanta
     */
    public function listeAvecPagination(
        $currentPage = 1,
        $sortField = 'id',
        $sortOrder = 'ASC',
        $maxPerPage = self::MAX_PER_PAGE
    ) {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.enabled = 1')
            ->addOrderBy('u.'.$sortField, $sortOrder);

        return PagerFantaTools::paginate($queryBuilder, $currentPage, $maxPerPage);
    }

    /**
     * Liste des utilisateurs désactivés.
     *
     * @param int    $currentPage
     * @param string $sortField
     * @param string $sortOrder
     * @param int    $maxPerPage
     *
     * @return Pagerfanta
     */
    public function listeAvecPaginationDesactives(
        $currentPage = 1,
        $sortField = 'id',
        $sortOrder = 'ASC',
        $maxPerPage = self::MAX_PER_PAGE
    ) {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.enabled = 0')
            ->addOrderBy('u.'.$sortField, $sortOrder);

        return PagerFantaTools::paginate($queryBuilder, $currentPage, $maxPerPage);
    }

    /**
     * Liste des utilisateurs non activés.
     *
     * @param int    $currentPage
     * @param string $sortField
     * @param string $sortOrder
     * @param int    $maxPerPage
     *
     * @return Pagerfanta
     *
     * @throws \Pagerfanta\Exception\NotIntegerMaxPerPageException
     * @throws \Pagerfanta\Exception\OutOfRangeCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerCurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1CurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1MaxPerPageException
     */
    public function listeAvecPaginationNonActives(
        $currentPage = 1,
        $sortField = 'id',
        $sortOrder = 'ASC',
        $maxPerPage = self::MAX_PER_PAGE
    ) {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.enabled = 0 AND u.lastLogin is null')
            ->addOrderBy('u.'.$sortField, $sortOrder);

        return PagerFantaTools::paginate($queryBuilder, $currentPage, $maxPerPage);
    }

    /**
     * Tableau d'utilisateur dont la clé est le trigramme de l'utilisateur.
     *
     * @return ArrayCollection|Utilisateur[]
     */
    public function listeUtilisateurIndexeParTrigramme()
    {
        $datas = [];
        /** @var Utilisateur[] $utilisateurs */
        $utilisateurs = $this->findAll();
        foreach ($utilisateurs as $utilisateur) {
            $datas[$utilisateur->getTrigramme()] = $utilisateur;
        }

        return $datas;
    }

    /**
     * Récupère une instance d'un super-admin.
     *
     * @return Utilisateur
     */
    public function getAdmin()
    {
        // TODO : le username de l'admin doit être récupéré depuis config.xml
        $username = 'aroban@masprovence.com';

        return $this->findOneBy(['username' => $username]);
    }

    use TrigrammeGeneratorTrait;

    /**
     * Liste tous les utilisateurs actifs.
     * L'objectif est de les utiliser dans un select2.
     *
     * @return QueryBuilder
     */
    public function selectAllActive()
    {
        return $this->createQueryBuilder('u')
            ->where('u.enabled = :enabled')
            ->setParameter('enabled', 1)
            ->orderBy('u.prenom', 'ASC')
            ->orderBy('u.nom', 'ASC');
    }

    /**
     * Liste par role les utilisateurs actifs.
     * L'objectif est de les utiliser dans un select2.
     *
     * @param string $roleCode
     *
     * @return QueryBuilder
     */
    public function selectAllByRole($roleCode)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin(Role::class, 'r', 'WITH', 'r.code = :code')
            ->innerJoin(
                UtilisateurRoleAttribution::class,
                'ura',
                'WITH',
                'ura.role = r.id and ura.utilisateur = u.id'
            )
            ->where('u.enabled = :enabled')
            ->setParameter('enabled', 1)
            ->setParameter('code', $roleCode)
            ->orderBy('u.nom', 'ASC')
            ->orderBy('u.prenom', 'ASC');
    }

    /**
     * Liste par role les utilisateurs actifs et par agence.
     *
     * @param string $roleCode
     * @param string $agenceId
     *
     * @return QueryBuilder
     */
    public function selectAllByRoleAndAgence($roleCode, $agenceId)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin(Role::class, 'r', 'WITH', 'r.code = :code')
            ->innerJoin(Agence::class, 'a', 'WITH', 'a.id = :agence')
            ->innerJoin(
                UtilisateurRoleAttribution::class,
                'ura',
                'WITH',
                'ura.role = r.id and ura.utilisateur = u.id and a MEMBER OF ura.agences '
            )
            ->where('u.enabled = :enabled')
            ->setParameter('enabled', 1)
            ->setParameter('code', $roleCode)
            ->setParameter('agence', $agenceId)
            ->orderBy('u.nom', 'ASC')
            ->orderBy('u.prenom', 'ASC');
    }
}
