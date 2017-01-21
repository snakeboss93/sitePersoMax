<?php

namespace App\Doctrine\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DateHeureTrait.
 */
trait DateHeureTrait
{
    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateHeure;

    /**
     * @return DateTime
     */
    public function getDateHeure()
    {
        return $this->dateHeure;
    }

    /**
     * @param DateTime $dateTime
     *
     * @return $this
     */
    public function setDateHeure(DateTime $dateTime)
    {
        $this->dateHeure = $dateTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateHeureString()
    {
        return $this->dateHeure->format('m-d-Y à H:i');
    }
}
