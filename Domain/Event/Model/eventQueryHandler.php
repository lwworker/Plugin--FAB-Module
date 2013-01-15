<?php

namespace Fab\Domain\Event\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;
use \lw_registry as lw_registry;
use \lw_db as lw_db;

class eventQueryHandler extends fabQueryHandler
{
    public function __construct(lw_db $db)
    {
        parent::__construct($db);
    }
    
    public function getEventIdByEventKey($key)
    {
        $this->db->setStatement("SELECT id FROM t:fab_tagungen WHERE v_schluessel = :key ");
        $this->db->bindParameter("key", "s", $key);
        $result = $this->db->pselect1();
        return $result['id'];
    }
    
    /**
     * Returns a list all saved events with all atributes in defined order
     * @return array
     */
    public function loadAllEvents()
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen ORDER BY anmeldefrist_beginn DESC ");
        return $this->db->pselect();
    }
    
    /**
     * Returns all saved data for a specific event
     * @param int $id
     * @return array
     */
    public function loadEventById($id)
    {
        return $this->baseGetEntryById($id, "fab_tagungen");
    }
    
    /**
     * Returns a list of all events where a certain email is saved as responsible
     * @param string $ansprechpartner_mail
     * @return array
     */
    public function loadEventsByResponsible($ansprechpartner_mail)
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE (ansprechpartner_mail = :ansprechpartner_mail OR stellvertreter_mail = :ansprechpartner_mail) AND anmeldefrist_beginn < :today AND anmeldefrist_ende > :today  ORDER BY anmeldefrist_beginn DESC ");
        $this->db->bindParameter("ansprechpartner_mail", "s", $ansprechpartner_mail);
        $this->db->bindParameter("today", "i", date("Ymd"));
        return $this->db->pselect();
    }
}
