<?php

namespace App\Model\Utilisateur;

/**
 * Interface UtilisateurManagerInterface.
 */
interface UtilisateurManagerInterface
{
    /**
     * @param Utilisateur $utilisateur
     */
    public function create(Utilisateur $utilisateur);

    /**
     * @param Utilisateur $utilisateur
     */
    public function delete(Utilisateur $utilisateur);

    /**
     * @param Utilisateur $utilisateur
     */
    public function update(Utilisateur $utilisateur);
}
