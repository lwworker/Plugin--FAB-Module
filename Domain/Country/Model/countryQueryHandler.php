<?php

namespace Fab\Domain\Country\Model;
use \lw_registry as lw_registry;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class countryQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
         parent::__construct($db);
    }
    
    /**
     * Returns a list of all saved countries from A to Z
     * @return array
     */
    public function getAllCountries()
    {
        $this->db->setStatement("SELECT * FROM t:fab_laender ORDER BY land ASC ");
        return $this->db->pselect();
    }
}