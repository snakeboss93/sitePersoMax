<?php

namespace AppBundle\Form\Common\Filtre\Dossier;

/**
 * Formulaire pour la filtrer les dossier.
 */
class FiltreDossierType extends AbstractFiltreDossier
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'filtreDoss';
    }
}
