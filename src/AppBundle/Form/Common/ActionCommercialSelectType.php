<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs de sélection des actions commercial.
 */
class ActionCommercialSelectType extends AbstractCommonType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'label' => 'app.dossier.form.actionCommerciale.label',
                'choices' => [
                    null => 'Choisissez une action',
                    'Contact reçu' => [
                        'Partenaire professionnel',
                        'Boutique',
                        'Clic site internet partenaire',
                        'Indéfini',
                        'P.A. terrain',
                        'Fiche produit',
                        'Organisme institutionnel',
                        'Mailing',
                        'Minitel / Pages Jaunes',
                        'Pub institutionnelle',
                        'Portes ouvertes',
                        'Panneau - fléchage',
                        'Relationnel non prof./parrainage',
                        'Stand (foire / salon / gal. marchande)',
                        'Boitage / Phoning',
                        'Moteur de rechercher Internet',
                    ],
                    'Contact créé' => [
                        'Partenaire professionnel',
                        'Phoning',
                        'Mailing',
                        'Relationnel non prof./parrainage',
                        'Panneau - Fléchage',
                        'Boitage / Phoning',
                    ],
                ],
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
