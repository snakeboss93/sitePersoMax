<?php

namespace AppBundle\Controller\Agenda;

use App\Doctrine\Types\Generic\GenericDureeType;
use App\Doctrine\Types\TitreAgendaItemType;
use App\Model\AgendaItem\AgendaItem;
use App\Model\AgendaItem\AgendaItemRepository;
use App\Model\EtudeDeProjet\EtudeDeProjet;
use App\Model\PermisDeConstruire\PermisDeConstruire;
use App\Model\Utilisateur\Utilisateur;
use App\Services\AgendaObserver\AgendaObserverInterface;
use AppBundle\Controller\CommonController;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controleur commun de l'agenda.
 */
class AgendaCommonController extends CommonController
{
    /**
     * @var AgendaObserverInterface $agendaObserver
     */
    protected $agendaObserver;

    /**
     * @var AgendaItemRepository $agendaItemRepository
     */
    protected $agendaItemRepository;

    /**
     * @var array $classNames
     */
    protected $classNames;


    /**
     * @param ContainerInterface|null $container
     *
     * @throws \Exception
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->agendaObserver = $this->container->get('app.services.agenda_observer');
        $this->agendaItemRepository = $this->getRepository(AgendaItem::class);
        $this->classNames = [
            'EtudeDeProjet' => new ReflectionClass(EtudeDeProjet::class),
            'PermisDeConstruire' => new ReflectionClass(PermisDeConstruire::class),
        ];
    }

    /**
     * Donne les nouveaux events en format array.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getNewData()
    {
        $results = $this->makeEvent(
            $this->agendaObserver->getEtudeDeProjetNew(),
            $this->classNames['EtudeDeProjet']->getShortName()
        );
        array_merge(
            $results,
            $this->makeEvent(
                $this->agendaObserver->getPermisDeConstruireNew(),
                $this->classNames['PermisDeConstruire']->getShortName()
            )
        );

        return $results;
    }

    /**
     * Donne les events en format JSON.
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    protected function getData()
    {
        $results = $this->makeAgendaItemEvent($this->agendaObserver->getAgendaItem());
        array_merge(
            $results,
            $this->makeEvent(
                $this->agendaObserver->getEtudeDeProjetAffecter(),
                $this->classNames['EtudeDeProjet']->getShortName()
            )
        );
        array_merge(
            $results,
            $this->makeEvent(
                $this->agendaObserver->getPermisDeConstruireAffecter(),
                $this->classNames['PermisDeConstruire']->getShortName()
            )
        );

        return json_encode($results);
    }

    /**
     * Donne les concepteurs BEA en format JSON.
     *
     * @return JsonResponse
     */
    protected function getConcepteurBEA()
    {
        return $this->getConcepteurJson($this->agendaObserver->getConcepteursBEA());
    }

    /**
     * Donne les concepteurs Agence en format JSON.
     *
     * @param $agence
     *
     * @return JsonResponse
     */
    protected function getConcepteurAgence($agence)
    {
        return $this->getConcepteurJson($this->agendaObserver->getConcepteursByAgence($agence));
    }

    /**
     * Permet de construire un tableau d'event en fonctions de la classe au format approprié pour FullCalendar.
     *
     * @param array  $events
     * @param string $class
     *
     * @return array
     *
     * @throws \Exception
     */
    private function makeEvent($events, $class)
    {
        $results = [];
        /** @var EtudeDeProjet|PermisDeConstruire $event */
        foreach ($events as $event) {
            $results[] = [
                'id' => $class.'-'.$event->getId(),
                'title' => $event->getShortClassTitle().' ['.$event->getPhase().']',
                'client' => $event->getDossier()->getContacts()->first(),
                'trigrammeAuteur' => $event->getUtilisateur()->getTrigramme(),
                'couleurAgence' => $event->getDossier()->getAgenceActuelle()->getCouleur(),
                'nomAgence' => $event->getDossier()->getAgenceActuelle(),
                'dateRetour' => $event->getDateRetour()->format('d/m'),
                'status' => $event->getStatut(),
                'editable' => $event->isVerrouille(),
                'nbQuartJournee' => null !== $event->getDuree() ? GenericDureeType::getReadableValue($event->getDuree()) : 1,
                'resourceId' => null !== $event->getConcepteur() ? $event->getConcepteur()->getId() : null,
                'start' => null !== $event->getPlanifie() ? $event->getPlanifie()->format('c') : null,
            ];
        }

        return $results;
    }

    /**
     * Permet de construire un tableau d'event d'agendaItem au format approprié pour FullCalendar.
     *
     * @param array $events
     *
     * @return array
     *
     * @throws \Exception
     */
    private function makeAgendaItemEvent($events)
    {
        $results = [];
        /** @var AgendaItem $event */
        foreach ($events as $event) {
            $results[] = [
                'id' => $event->getId(),
                'title' => TitreAgendaItemType::getReadableValue($event->getTitre()),
                'couleurAgence' => 'default',
                'editable' => $event->isStringverrouille(),
                'nbQuartJournee' => null !== $event->getDuree() ? GenericDureeType::getReadableValue($event->getDuree()) : 1,
                'resourceId' => null !== $event->getConcepteur() ? $event->getConcepteur()->getId() : null,
                'start' => null !== $event->getPlanifie() ? $event->getPlanifie()->format('c') : null,
                'dateRetour' => null,
                'status' => null,
                'client' => null,
                'trigrammeAuteur' => null,
                'nomAgence' => null,
            ];
        }

        return $results;
    }

    /**
     * Donne les concepteur au format approprié à FullCalendar.
     *
     * @param array $concepteurs
     *
     * @return JsonResponse
     */
    private function getConcepteurJson($concepteurs)
    {
        $results = [];
        /** @var Utilisateur $concepteur */
        foreach ($concepteurs as $concepteur) {
            $results[] = [
                'id' => $concepteur->getId(),
                'title' => '<span>'.$concepteur->getPrenom().'</span>',
            ];
        }

        return json_encode($results);
    }
}
