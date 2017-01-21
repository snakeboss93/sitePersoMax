<?php

namespace AppBundle\Controller\Agenda;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Controleur de l'agenda.
 *
 * @Route("/agenda")
 */
final class AgendaController extends AgendaCommonController
{
    /**
     * Page de listing de l'agenda.
     *
     * @Route("/bea", name="agenda.liste.bea")
     *
     * @return Response
     */
    public function listeBEAAction()
    {
//        $this->securityService->checkAccess(Agenda::ACTION_LISTER);

        return $this->render(
            ':app/agenda:lister.html.twig',
            [
                'newData' => $this->getNewData(),
                'data' => $this->getData(),
                'concepteur' => $this->getConcepteurBEA(),
            ]
        );
    }

    /**
     * Page de listing de l'agenda.
     *
     * @Route("/agence", name="agenda.liste.agence")
     *
     * @return Response
     */
    public function listeAgenceAction()
    {
//        $this->securityService->checkAccess(Agenda::ACTION_LISTER);

        return $this->render(
            ':app/agenda:lister.html.twig',
            [
                'newData' => $this->getNewData(),
                'data' => $this->getData(),
                'concepteur' => $this->getConcepteurAgence($this->currentAgence),
            ]
        );
    }
}
