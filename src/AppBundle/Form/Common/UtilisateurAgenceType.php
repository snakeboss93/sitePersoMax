<?php

namespace AppBundle\Form\Common;

use App\Doctrine\Criterias\UtilisateurRoleAttributionRole;
use App\Model\Agence\Agence;
use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use App\Model\UtilisateurRoleAttribution\UtilisateurRoleAttribution;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Champs pour le widget utilisateur agence.
 * Permet de générer un select qui contient à la fois l'identifiant utilisateur et l'identifiant agence.
 */
class UtilisateurAgenceType extends AbstractUtilisateurAgenceType
{
    /**
     * @param Agence $agenceSession
     *
     * @return null|string
     */
    public function setOptionUtilisateurConnecte($agenceSession)
    {
        // Si une agence est en session, alors l'utilisateur courant sera retourné en premier.
        $currentUserKey = null;
        if (null !== $agenceSession) {
            /** @var Utilisateur $utilisateurConnecte */
            $utilisateurConnecte = $this->tokenStorage->getToken()->getUser();
            $currentUserKey = $utilisateurConnecte->getId().'-'.$agenceSession->getId();
            $currentUserValue = 'Moi-même - '.$agenceSession->getNomInterne().' ('.$agenceSession->getTrigramme().')';
            $this->options[$currentUserKey] = $currentUserValue;

            return $currentUserKey;
        }
    }

    /**
     * Responsable commercial.
     *
     * @param string $currentUserKey
     * @param Agence $agence
     */
    public function setOptionsResponsableCommercialAgence($currentUserKey, Agence $agence)
    {
        $utilisateursRoleAttribution = $this->doctrine->getRepository(
            UtilisateurRoleAttribution::class
        )->getUsersByAgenceAndRole($agence, Role::RESPONSABLE_COMMERCIAL);

        /** @var UtilisateurRoleAttribution $ura */
        foreach ($utilisateursRoleAttribution as $ura) {
            if (null !== $ura->getAgences() && null !== $ura->getAgences()[0] && null !== $ura->getUtilisateur()) {
                $u = $ura->getUtilisateur();
                $key = $u->getId().'-'.$ura->getAgences()[0]->getId();
                $value = $u->getFullname().' - '.$ura->getAgences()[0]->getNomInterne().' ('.$ura->getAgences()[0]->getTrigramme().')';
                if ($currentUserKey !== $key) {
                    $this->options[$key] = $value;
                }

                /** @var Utilisateur $utilisateurConnecte */
                $utilisateurConnecte = $this->tokenStorage->getToken()->getUser();
                $currentUserKey = $utilisateurConnecte->getId().'-'.$agence->getId();
                $currentUserValue = 'Moi-même - '.$agence->getNomInterne().' ('.$agence->getTrigramme().')';
                $this->options[$currentUserKey] = $currentUserValue;
            }
        }
    }

    /**
     * Génère une liste de responsables commerciaux.
     *
     * @param string $currentUserKey
     */
    public function setOptionsListeResponsablesCommerciaux($currentUserKey)
    {
        $criterias = [];
        $role = $this->doctrine->getRepository(Role::class)->findOneBy(
            ['code' => Role::RESPONSABLE_COMMERCIAL]
        );
        $criterias[] = new UtilisateurRoleAttributionRole($role->getId());

        $this->setOptionsByCriterias($criterias, $currentUserKey);
    }

    /**
     * Construit le tableau des options.
     *
     * @return array
     */
    public function getOptions()
    {
        $repoUra = $this->doctrine->getRepository(UtilisateurRoleAttribution::class);
        /** @var Agence $agenceSession */
        $agenceSession = $this->session->get('agence');

        $utilisateurConnecte = null;
        if (null !== $this->tokenStorage->getToken()) {
            /** @var Utilisateur $utilisateurConnecte */
            $utilisateurConnecte = $this->tokenStorage->getToken()->getUser();
        }

        $this->options = [];
        $currentUserKey = null;

        // Si ni agent co, ni commercial, alors on ne propose que les RC dans la liste.
        $isUserConnecteAgentCommercial = $repoUra->isRole(Role::AGENT_COMMERCIAL, $utilisateurConnecte);
        $isUserConnecteCommercial = $repoUra->isRole(Role::COMMERCIAL, $utilisateurConnecte);

        // En tant que commercial ou agent commercial.
        if ($isUserConnecteAgentCommercial || $isUserConnecteCommercial) {
            // Utilisateur courant dans la liste.
            $currentUserKey = $this->setOptionUtilisateurConnecte($agenceSession);
        }

        // En tant que personne connectée avec une agence mais ni commercial ni agent commercial
        if (!$isUserConnecteAgentCommercial && !$isUserConnecteCommercial && null === $agenceSession) {
            // Par défaut au RC de l'agence
        }

        // Dans tous les cas, lister tous les RC
        $this->setOptionsListeResponsablesCommerciaux($currentUserKey);

        return $this->options;
    }
}
