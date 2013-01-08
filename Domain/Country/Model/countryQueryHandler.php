<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;

class countryQueryHandler
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
    }
    
    public function getAllCountries()
    {
        $this->db->setStatement("SELECT * FROM t:tabelle ORDER BY countryname DESC ");
        return $this->db->pselect();
    }
}