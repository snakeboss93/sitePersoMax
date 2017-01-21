<?php

namespace AppBundle\Controller;

use App\Model\Agence\Agence;
use App\Model\Contact\Contact;
use App\Model\Courrier\Courrier;
use App\Model\Courrier\CourrierModele\CourrierModele;
use App\Model\Courrier\CourrierModele\CourrierModeleRepository;
use App\Model\Dossier\Dossier;
use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttributionRepository;
use App\Model\Ville\Ville;
use App\Services\SecurityService\SecurityService;
use AppBundle\Exception\NoAgenceException;
use AppBundle\Exception\NoRoleRightException;
use AppBundle\Service\CourrierService;
use AppBundle\Service\HttpService;
use Doctrine\Common\Persistence\ObjectRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Controleur de générale.
 */
class CommonController extends Controller
{
    /**
     * @var SecurityService
     */
    protected $securityService;

    /**
     * @var HttpService
     */
    protected $httpService;

    /**
     * @var DataCollectorTranslator
     */
    protected $translator;

    /**
     * @var UtilisateurRoleAttributionRepository
     */
    protected $roleAttributionRepository;

    /**
     * @var Agence|null
     */
    protected $currentAgence = null;

    /**
     * @var Utilisateur
     */
    protected $currentUtilisateur = null;

    /**
     * @var CourrierModeleRepository
     */
    protected $repoCourrierModele;

    /**
     * @var CourrierService
     */
    protected $serviceCourrier;

    /**
     * @param ContainerInterface|null $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \LogicException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->securityService = $this->container->get('app.services.security_service');
        $this->httpService = $this->get('app.services.http_service');
        $this->translator = $this->get('translator');
        $this->roleAttributionRepository = $this->getRepository(UtilisateurRoleAttribution::class);
        $this->currentAgence = $this->container->get('session')->get('agence', null);
        if (null !== $this->currentAgence) {
            $this->currentAgence = $this->getRepository(Agence::class)->find($this->currentAgence);
        }
        $token = $this->get('security.token_storage')->getToken();
        if (null !== $token) {
            $this->currentUtilisateur = $this->get('security.token_storage')->getToken()->getUser();
        }
        $this->repoCourrierModele = $this->getRepository(CourrierModele::class);
        $this->serviceCourrier = $this->container->get('app.courrier_service');
    }

    /**
     * Fabrique du formulaire de suppression et de modification.
     *
     * Penser à mettre l'annotation method('DELETE') ou method('POST').
     *
     * @param string $route
     * @param int    $id
     * @param string $method
     * @param array  $params Paramètres additionnels (par exemple un autre id dans l'url)
     *
     * @return \Symfony\Component\Form\Form
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function createInlineFormBuilder($route, $id, $method = 'DELETE', $params = null)
    {
        $formPrefix = str_replace('.', '_', $route).'_'.strtolower($method);

        $routeParams = ['id' => $id];
        if (null !== $params) {
            $routeParams = array_merge($routeParams, $params);
        }

        return $this->container->get('form.factory')
            ->createNamedBuilder('form_'.$formPrefix.'_'.$id)
            ->setAttribute('name', 'form_'.$formPrefix.'_'.$id)
            ->setAction($this->generateUrl($route, $routeParams))
            ->setMethod($method)
            ->getForm();
    }

    /**
     * @param string     $type
     * @param Pagerfanta $pager
     * @param string     $method
     *
     * @return array
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function inlineFormList($type, $pager, $method = 'DELETE')
    {
        $forms = [];
        foreach ($pager as $item) {
            $id = is_object($item) ? $item->getId() : $item['id'];
            $forms[$id] = $this->createInlineFormBuilder($type, $id, $method)->createView();
        }

        return $forms;
    }

    /**
     * @param array $rolesRight
     *
     * @throws NoRoleRightException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function redirectIfNoRoleRight(array $rolesRight)
    {
        if (!$this->roleAttributionRepository->hasArrayCodeRoleByUser(
            $rolesRight,
            $this->currentUtilisateur
        )
        ) {
            $this->addFlash('warning', $this->translator->trans('appbundle.permission.denied'));

            $exception = new NoRoleRightException();
            $exception->setRedirectRoute('tableau_de_bord');
            throw $exception;
        }
    }

    /**
     * @param Contact $contact
     *
     * @throws NoRoleRightException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function redirectIfAgentCommercialAndNotOwner(Contact $contact)
    {
        $isAgentCo = $this->roleAttributionRepository->isRole(Role::AGENT_COMMERCIAL, $this->currentUtilisateur);

        if ($isAgentCo && $contact->getCreatedBy() !== $this->currentUtilisateur) {
            $this->addFlash('warning', $this->translator->trans('appbundle.permission.denied'));

            $exception = new NoRoleRightException();
            $exception->setRedirectRoute('tableau_de_bord');
            throw $exception;
        }
    }

    /**
     * Lancer une exception si l'utilisateur ne dispose pas d'Agence.
     *
     * @param string $route
     *
     * @throws NoAgenceException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function redirectIfNoAgence($route = 'tableau_de_bord')
    {
        if (null === $this->currentAgence) {
            $this->addFlash('warning', $this->translator->trans('appbundle.dossier.agence.needed'));

            $exception = new NoAgenceException();
            $exception->setRedirectRoute($route);
            throw $exception;
        }
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @codeCoverageIgnore
     *
     * @param string|int               $name    The name of the form
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return Form
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function createNamedForm($name, $type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->createNamed($name, $type, $data, $options);
    }

    /**
     * Récupere la master route utile dans le cas des fragments.
     *
     * @return mixed
     */
    protected function getMasterRoute()
    {
        $stack = $this->get('request_stack');
        $masterRequest = $stack->getMasterRequest();

        return $masterRequest->get('_route');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToDashboard()
    {
        $redirectUrl = $this->generateUrl('tableau_de_bord');

        return $this->redirect($redirectUrl);
    }

    /**
     * Gets the ObjectRepository for an persistent object.
     *
     * @param string $persistentObjectName  The name of the persistent object.
     * @param string $persistentManagerName The object manager name (null for the default one).
     *
     * @return ObjectRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->getDoctrine()->getRepository($persistentObjectName, $persistentManagerName);
    }

    /**
     * @param Request $request
     * @param string  $submit
     *
     * @return bool
     */
    protected function isSubmitButtonIs(Request $request, $submit)
    {
        return $request->get($submit, false) !== false;
    }

    /**
     * @param Request $request
     * @param string  $variable
     * @param string  $entity
     * @param string  $field
     *
     * @return object|void
     */
    protected function fetchSelected(Request $request, $variable, $entity, $field)
    {
        if (null !== $request->query->get($variable)[$field]) {
            return $this->getRepository($entity)->find(
                $request->query->get($variable)[$field]
            );
        }

        return;
    }

    /**
     * Récupère le contact sélectionné en fonction du paramètre présent dans l'url.
     *
     * @param Request $request
     * @param string  $variable
     *
     * @return Contact|null|object
     */
    protected function fetchContactSelected(Request $request, $variable)
    {
        return $this->fetchSelected($request, $variable, Contact::class, 'contact');
    }

    /**
     * Récupère la ville sélectionnée en fonction du paramètre présent dans l'url.
     *
     * @param Request $request
     * @param string  $variable
     * @param string  $field
     *
     * @return Ville|null|object
     */
    protected function fetchVilleSelected(Request $request, $variable, $field = 'villeConstruction')
    {
        return $this->fetchSelected($request, $variable, Ville::class, $field);
    }

    /**
     * @param string      $route
     * @param array       $params
     * @param string|null $flashLevel
     * @param string|null $flashMessage
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \LogicException
     */
    protected function redirectWithFlashMessage($route, array $params = [], $flashLevel = null, $flashMessage = null)
    {
        if ($flashLevel !== null && $flashMessage !== null) {
            $this->addFlash($flashLevel, $flashMessage);
        }

        return $this->redirectToRoute($route, $params);
    }

    /**
     * @param Dossier  $dossier
     * @param int      $etapeId
     * @param int|null $phase
     *
     * @return Courrier
     */
    protected function initCourrier(Dossier $dossier, $etapeId, $phase = null)
    {
        $courrier = (new Courrier())
            ->setDossier($dossier)
            ->setEtape($etapeId)
            ->setBrouillon(true)
            ->setUtilisateurSignature($this->currentUtilisateur);

        if (null !== $phase) {
            $courrier->setPhase($phase);
        }

        return $courrier;
    }

    /**
     * Pré-rempli le courrier avec le modèle.
     *
     * @param Courrier            $courrier
     * @param CourrierModele|null $courrierModele
     *
     * @return Courrier
     */
    protected function setCourrierContentFromModel(Courrier $courrier, $courrierModele = null)
    {
        if (null !== $courrierModele) {
            $this->serviceCourrier->setDossier($courrier->getDossier());
            $this->serviceCourrier->setUtilisateur($this->currentUtilisateur);
            $this->serviceCourrier->setAgence($this->currentAgence);

            $contenu = $courrierModele->getContenu();
            $courrier->setCourrierModele($courrierModele);
            $courrier->setObjet($courrierModele->getObjet());
            $courrier->setContenu($contenu);
        }

        return $courrier;
    }

    /**
     * Préfixe le code d'un email ou d'un courrier par le trigramme de la société de l'agence en cours.
     *
     * @param string $code
     *
     * @return string
     */
    protected function prefixCodeEmailCourrier($code)
    {
        return $this->currentAgence->getSociete()->getTrigramme().'_'.$code;
    }
}
