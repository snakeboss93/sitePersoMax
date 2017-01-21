<?php

namespace App\Model\AgendaItem;

use App\Doctrine\Traits\BlameableTrait;
use App\Doctrine\Traits\ConcepteurTrait;
use App\Doctrine\Traits\Date\PlanifieTrait;
use App\Doctrine\Traits\Generic\GenericDureeTrait;
use App\Doctrine\Traits\Generic\GenericVerrouilleTrait;
use App\Doctrine\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Class AgendaItem.
 *
 * @ORM\Entity(repositoryClass="App\Model\AgendaItem\AgendaItemRepository")
 */
class AgendaItem
{
    const ACTION_CREER = 'AGENDAITEM:CREER';
    const ACTION_MODIFIER = 'AGENDAITEM:MODIFIER';
    const ACTION_LISTER = 'AGENDAITEM:LISTER';

    use IdTrait;

    /**
     * @ORM\Column(type="TitreAgendaItemType")
     * @DoctrineAssert\Enum(entity="App\Doctrine\Types\TitreAgendaItemType")
     * @Assert\NotBlank(groups={"Default", "create", "edit", "import", "light"})
     */
    protected $titre;

    use ConcepteurTrait;
    use GenericDureeTrait;
    use GenericVerrouilleTrait;
    use PlanifieTrait;
    use BlameableTrait;
    use TimestampableEntity;

    /**
     * @param string $titre
     *
     * @return AgendaItem
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }
}
