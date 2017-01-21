<?php

namespace AppBundle\Controller;

use App\Model\Agence\AgenceManagerInterface;
use App\Model\Agence\AgenceRepository;
use AppBundle\Form\Agence\AgenceParametreType;
use AppBundle\Form\Agence\AgenceType;
use App\Model\Agence\Agence;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controleur des agences.
 */
class AgenceController extends CommonController
{
    /**
     * @var AgenceManagerInterface
     */
    protected $manager;

    /**
     * @var AgenceRepository
     */
    protected $repo;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->manager = $this->container->get('app.agence_manager');
        $this->repo = $this->getRepository(Agence::class);
    }

    /**
     * Permet de chosir une agence et l'enregistre dans le session.
     *
     *
     * @Route("/agence/selectionner/{id}", name="agence.selection")
     *
     * @param Agence $agence
     *
     * @return RedirectResponse
     */
    public function selectCurrentAgenceAction(Agence $agence)
    {
        $session = new Session();
        $session->set('agence', $agence);
        $session->set('toSelectAgence', false);
        $referer = $this->httpService->getReferer($this->generateUrl('tableau_de_bord'));

        return new RedirectResponse($referer);
    }

    /**
     * Page de création d'une agence.
     *
     * @Route("/agence/creer", name="agence.creer")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     */
    public function creerAction(Request $request)
    {
        $this->securityService->checkAccess(Agence::ACTION_CREER);

        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence, ['validation_groups' => ['create']]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->manager->create($agence);
                $this->addFlash('success', $this->translator->trans('appbundle.agence.creer.success'));

                return $this->redirect($this->generateUrl('agence.liste'));
            }
            $this->addFlash('warning', $this->translator->trans('appbundle.agence.creer.warning'));
        }

        return $this->render(':app/agence:creer.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Page de listing d'une agence.
     *
     * @Route("/agence", name="agence.liste")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listeAction(Request $request)
    {
        $this->securityService->checkAccess(Agence::ACTION_LISTER);
        list($sortField, $sortOrder) = $this->httpService->getSortableParameters('societe', 'ASC');
        $pager = $this->repo->listeAvecPagination($request->get('page', 1), $sortField, $sortOrder);

        return $this->render(':app/agence:liste.html.twig', ['pager' => $pager]);
    }

    /**
     * Page de modification d'une agence.
     *
     * @Route("/agence/edit/{id}", name="agence.edit")
     * @ParamConverter("agence", class="App\Model\Agence\Agence")
     *
     * @param Request $request
     * @param Agence  $agence
     *
     * @return Response
     *
     * @throws \LogicException
     *
     * @internal param int $id
     */
    public function editAction(Request $request, Agence $agence)
    {
        $this->securityService->checkAccess(Agence::ACTION_MODIFIER, $agence);

        $form = $this->createForm(AgenceType::class, $agence, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->manager->update($agence);
                $this->addFlash('success', $this->translator->trans('appbundle.agence.edit.success'));

                return $this->redirect($this->generateUrl('agence.liste'));
            }
            $this->addFlash('warning', $this->translator->trans('appbundle.agence.edit.warning'));
        } else {
            $form->get('referer')->setData($this->httpService->getReferer());
        }

        return $this->render(':app/agence:edit.html.twig', ['form' => $form->createView(), 'agence' => $agence]);
    }

    /**
     * Page de modification des parametres d'une agence.
     *
     * @Route("/agence/parametre", name="agence.parametre")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editParametreAction(Request $request)
    {
        //        $this->securityService->checkAccess(Agence::ACTION_MODIFIER);

        $agence = $this->currentAgence;

        $form = $this->createForm(AgenceParametreType::class, $agence, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->manager->update($agence);

                return $this->redirectWithFlashMessage('agence.parametre', [], 'success', $this->translator->trans('appbundle.agence.parametre.success'));
            } else {
                $this->addFlash('warning', $this->translator->trans('appbundle.agence.parametre.warning'));
            }
        }

        return $this->render(':app/agence:parametre.html.twig', ['form' => $form->createView(), 'agence' => $agence]);
    }
}
