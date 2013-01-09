<?php

namespace Fab\Domain\Country\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;
use \lw_registry as lw_registry;
use \lw_db as lw_db;

class countryQueryHandler extends fabQueryHandler
{
    public function __construct(lw_db $db)
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
