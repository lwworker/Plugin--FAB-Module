<?php

namespace Fab\Domain\Participant\Model;

class participantQueryHandler
{
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry('db');
    }
    
}
