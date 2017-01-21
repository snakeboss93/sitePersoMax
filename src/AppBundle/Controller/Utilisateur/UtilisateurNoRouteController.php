<?php

namespace AppBundle\Controller\Utilisateur;

use App\Model\Utilisateur\UtilisateurManagerInterface;
use App\Model\Utilisateur\UtilisateurRepository;
use AppBundle\Controller\CommonController;
use App\Model\Utilisateur\Utilisateur;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UtilisateurNoRouteController.
 */
class UtilisateurNoRouteController extends CommonController
{
    /**
     * @var UtilisateurManagerInterface
     */
    protected $manager;

    /**
     * @var UtilisateurRepository
     */
    protected $repo;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->manager = $this->container->get('app.utilisateur_manager');
        $this->repo = $this->getRepository(Utilisateur::class);
    }

    /**
     * Affiche le nom, prénom, trigramme d'un utilisateur.
     *
     * @param Utilisateur|null $utilisateur
     *
     * @return Response
     */
    public function fullnameAction(Utilisateur $utilisateur = null)
    {
        return $this->render(
            ':app/utilisateur/noroute:fullname.html.twig',
            [
                'utilisateur' => $utilisateur,
            ]
        );
    }
}
