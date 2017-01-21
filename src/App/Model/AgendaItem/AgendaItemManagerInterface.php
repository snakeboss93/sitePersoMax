<?php

namespace App\Model\AgendaItem;

/**
 * Interface ActionManagerInterface.
 */
interface AgendaItemManagerInterface
{
    /**
     * @param AgendaItem $agendaItem
     */
    public function create($agendaItem);

    /**
     * @param AgendaItem $agendaItem
     */
    public function update($agendaItem);
}
