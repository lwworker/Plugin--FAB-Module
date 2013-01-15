<?php

namespace Fab\Domain\Event\Specification;
use \Fab\Domain\Event\Object\event as event;

class isDeletable 
{
    public function __construct()
    {
    }
    
    static public function getInstance()
    {
        return new isDeletable();
    }
    
    public function isSatisfiedBy(event $event)
    {
        if ($event->getValueByKey('anmeldefrist_beginn') > date("Ymd") || $event->getValueByKey('anmeldefrist_ende') < date("Ymd")) {
            return true;
        }
        return false;
    }
}