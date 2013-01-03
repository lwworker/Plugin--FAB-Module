<?php

namespace FabBackend\Object;

class eventAggregateFactory
{
    static public function buildAggregateFromDomainEvent($domainEvent, $queryHandler) 
    {
        $items = array();
        $cmd = $domainEvent->getEventName();
        if ($cmd == "showListAction") {
            $items = $queryHandler->getAllEvents();
        }
        foreach($items as $item) {
            $entities[] = new FabBackend\Object\event($item);
        }
        return new \lw_ddd_entityAggregate($entities);
    }
}
