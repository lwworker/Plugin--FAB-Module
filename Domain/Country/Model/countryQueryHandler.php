<?php

namespace Fab\Domain\Country\Model;
use \lw_registry as lw_registry;

class countryQueryHandler
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
    }
    
    public function getAllCountries()
    {
        $this->db->setStatement("SELECT * FROM t:fab_laender ORDER BY land ASC ");
        return $this->db->pselect();
    }
}