<?php

namespace \Fab\Library;

class fabCommandHandler 
{

    public function __construct()
    {
        
    }
    
    public function handle($domainEvent)
    {
        $command = $domainEvent->getEventName();
        $this->$command($domainEvent->getEntity());
    }
}