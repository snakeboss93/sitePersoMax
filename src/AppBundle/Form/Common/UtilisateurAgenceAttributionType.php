<?php

namespace AppBundle\Form\Common;

use App\Doctrine\Criterias\UtilisateurRoleAttributionAgence;
use App\Doctrine\Criterias\UtilisateurRoleAttributionRole;
use App\Doctrine\Criterias\UtilisateurRoleAttributionRoleIn;
use App\Model\Agence\Agence;
use App\Model\Role\Role;
use App\Model\Utilisateur\Utilisateur;
use AppBundle\Form\Transformer\AttributionFieldDataTransformer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Champs pour le widget utilisateur agence.
 * Permet de générer un select qui contient à la fois l'identifiant utilisateur et l'identifiant agence.
 *
 * La liste comprendra les commerciaux et agent commerciaux de l'agence courante ainsi que tous les RC.
 */
class UtilisateurAgenceAttributionType extends AbstractUtilisateurAgenceType
{
    /**
     * Construit le tableau des options.
     *
     * @return array
     */
    public function getOptions()
    {
        /** @var Agence $agenceSession */
        $agenceSession = $this->session->get('agence');
        $this->options = [];
        $currentUserKey = null;
        $currentUserKey = $this->setOptionUtilisateurConnecte($agenceSession);
        $criteria = [];
        $criterias = [];

        $role = $this->doctrine->getRepository(Role::class)->findOneBy(
            ['code' => Role::RESPONSABLE_COMMERCIAL]
        );
        $criteria[] = new UtilisateurRoleAttributionRole($role->getId());
        $this->setOptionsByCriterias($criteria, $currentUserKey);

        $roleC = $this->doctrine->getRepository(Role::class)->findOneBy(
            ['code' => Role::COMMERCIAL]
        );
        $roleAC = $this->doctrine->getRepository(Role::class)->findOneBy(
            ['code' => Role::AGENT_COMMERCIAL]
        );
        $criterias[] = new UtilisateurRoleAttributionRoleIn([$role->getId(), $roleC->getId(), $roleAC->getId()]);
        $criterias[] = new UtilisateurRoleAttributionAgence($agenceSession->getId());

        $this->setOptionsByCriterias($criterias, $currentUserKey);

        return $this->options;
    }

    /**
     * @param Agence $agenceSession
     *
     * @return null|string
     */
    public function setOptionUtilisateurConnecte($agenceSession)
    {
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new AttributionFieldDataTransformer($this->doctrine));
    }
}
