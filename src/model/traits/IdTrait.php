<?php

namespace ifacebook\model\traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * trait IdTrait.
 */
trait IdTrait
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @var int $id
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
