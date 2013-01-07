<?php

namespace Fab\Domain\Participant\Model;

class participantCommandHandler
{
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry('db');
    }
    
    public function handle($domainEvent)
    {
        $command = $domainEvent->getEventName();
        $this->$command($domainEvent->getEntity());
    }

    public function setDebug($bool = true)
    {
        if($bool === true){
            $this->debug = true;
        }else{
            $this->debug = false;
        }
    }
    
}
