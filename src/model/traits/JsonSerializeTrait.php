<?php

namespace ifacebook\model\traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * trait JsonSerializeTrait.
 */
trait JsonSerializeTrait
{
    /**
     * Permet de sérialiser notre objet en json.
     * Utilisé pour les requete ajax.
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return json_encode($this->arraySerialize());
    }

    /**
     * Permet de sérialiser notre objet en array.
     *
     * @return array
     */
    protected function arraySerialize()
    {
        return get_object_vars($this);
    }
}
