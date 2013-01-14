<?php

namespace Fab\Domain\Replacement\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;
use \lw_registry as lw_registry;
use \lw_db as lw_db;

class replacementQueryHandler extends fabQueryHandler
{
    public function __construct(lw_db $db)
    {
        parent::__construct($db);
    }

    /**
     * Returns all saved data for a specific event
     * @param int $id
     * @return array
     */
    public function loadReplacementByEventId($eventId)
    {
        $this->db->setStatement("SELECT id, stellvertreter_mail FROM t:fab_tagungen WHERE id = :id ");
        $this->db->bindParameter("id", "i", $eventId);
        return $this->db->pselect1();
    }
}