<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;

class textQueryHandler
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
    }
    
    public function getAllTextsByCategory()
    {
        $this->db->setStatement("SELECT * FROM t:tabelle DESC ");
        return $this->db->pselect();
    }
}