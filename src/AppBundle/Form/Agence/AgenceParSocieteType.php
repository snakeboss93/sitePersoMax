<?php

namespace AppBundle\Form\Agence;

use App\Model\Agence\Agence;
use App\Model\Societe\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de selection des agences par sociétés.
 */
class AgenceParSocieteType extends EntityType
{
    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $societeRepository = $this->registry->getRepository(Societe::class);
        $choices = $this->registry->getRepository(Agence::class)->listeParNomSociete($societeRepository);

        $resolver->setDefaults(
            [
                'class' => 'App\Model\Agence\Agence',
                'property' => 'nomInterne',
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'agence_by_societe';
    }
}
