<?php

namespace AppBundle\Controller\Agenda;

use App\Model\AgendaItem\AgendaItemManager;
use App\Model\AgendaItem\AgendaItemRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Controleur de l'agenda pour les opération ajax.
 *
 * @Route("/agenda")
 */
final class AgendaAjaxController extends AgendaCommonController
{
    /**
     *
     * @Route("/api/update", name="agenda.api.update", condition="request.isXmlHttpRequest()", defaults={"_format": "json"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function updateDataAjaxAction(Request $request)
    {
        /** @var array $events */
        $events = json_decode($request->request->get('events'));
        $reussi = [];
        /** @var \stdClass $event */
        foreach ($events as $event) {
            $classNameAndid = explode('-', $event->id);
            $reussi[] = $this->agendaObserver->updateOrCreateProcess(
                $event,
                $this->managerProcess($classNameAndid[0]),
                $this->repoProcess($classNameAndid[0])
            );
        }

        return new JsonResponse($reussi);
    }

    /**
     *
     * @Route("/get/item/id", name="agenda.get.item.id", condition="request.isXmlHttpRequest()", defaults={"_format": "json"})
     *
     * @return JsonResponse
     */
    public function getItemIdAjaxAction()
    {
        return new JsonResponse(['lastId' => $this->agendaItemRepository->findOneBy([], ['id' => 'DESC'])->getId()]);
    }

    /**
     * Permet de choisir le manager adquat à l'objet.
     *
     * @param string $eventClassName
     *
     * @return AgendaItemManager|ObjectManager
     *
     * @throws \Exception
     */
    private function managerProcess($eventClassName)
    {
        /** @var ReflectionClass $className */
        foreach ($this->classNames as $className) {
            if (false !== strpos($className->getShortName(), $eventClassName)) {
                return $this->container->get('doctrine')->getManagerForClass($className->getName());
            }
        }

        return $this->container->get('app.agenda_manager');
    }

    /**
     * Permet de choisir le repository adquat à l'objet.
     *
     * @param string $eventClassName
     *
     * @return AgendaItemRepository|ObjectRepository
     */
    private function repoProcess($eventClassName)
    {
        /** @var ReflectionClass $className */
        foreach ($this->classNames as $className) {
            if (false !== strpos($className->getShortName(), $eventClassName)) {
                return $this->getRepository($className->getName());
            }
        }

        return $this->agendaItemRepository;
    }
}
