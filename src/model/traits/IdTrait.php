<?php

namespace maxime\model\traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * trait IdTrait.
 */
trait IdTrait
{
    /**
     * Identifiant unique.
     *
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
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
