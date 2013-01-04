<?php

namespace FabBackend\Model;

class eventQueryHandler
{
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry('db');
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
}
