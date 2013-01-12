<?php

namespace Fab\Domain\Participant\Specification;
use \Fab\Domain\Participant\Object\participant as participant;

class isDeletable 
{
    public function __construct()
    {
    }
    
    static public function getInstance()
    {
        return new isDeletable();
    }
    
    public function isSatisfiedBy(participant $participant)
    {
        return true;
    }
}