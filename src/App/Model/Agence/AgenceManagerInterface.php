<?php

namespace App\Model\Agence;

/**
 * Interface ActionManagerInterface.
 */
interface AgenceManagerInterface
{
    /**
     * @param Agence $agence
     */
    public function create($agence);

    /**
     * @param Agence $agence
     */
    public function update($agence);
}
