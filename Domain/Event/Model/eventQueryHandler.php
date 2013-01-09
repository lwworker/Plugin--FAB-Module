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
    
    public function getAllEvents()
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen ORDER BY anmeldefrist_beginn DESC ");
        return $this->db->pselect();
    }
    
    public function getEventById($id)
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
    
    public function loadEventsByResponsible($ansprechpartner_mail)
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE ansprechpartner_mail = :ansprechpartner_mail ORDER BY anmeldefrist_beginn DESC ");
        $this->db->bindParameter("ansprechpartner_mail","s",$ansprechpartner_mail);
        return $this->db->pselect();
    }
}