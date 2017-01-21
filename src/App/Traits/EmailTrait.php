<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EmailTrait.
 */
trait EmailTrait
{
    /**
     * Email.
     *
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Length(max="255", groups={"Default", "create", "edit", "import", "light"})
     */
    protected $email;

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}
