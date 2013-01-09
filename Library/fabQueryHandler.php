<?php

namespace Fab\Library;
use \lw_db as lw_db;

class fabQueryHandler 
{
    public function __construct(lw_db $db)
    {
        $this->db = $db;
    }
}