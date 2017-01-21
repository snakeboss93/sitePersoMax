<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class IdTrait.
 */
trait IdTrait
{
    /**
     * Identifiant unique.
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
