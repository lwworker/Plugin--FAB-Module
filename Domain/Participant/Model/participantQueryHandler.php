<?php

namespace Fab\Domain\Participant\Model;

class participantQueryHandler
{
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry('db');
    }
    
    public function loadParticipantsByEvent($event_id)
    {
        $this->db->setStatement("SELECT * FROM t:fab_teilnehmer WHERE event_id = :event_id ORDER BY nachname ASC ");
        $this->db->bindParameter("event_id", "i", $event_id);
        return $this->db->pselect();
    }

    public function loadParticipantById($id)
    {
        $this->db->setStatement("SELECT * FROM t:fab_teilnehmer WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
}