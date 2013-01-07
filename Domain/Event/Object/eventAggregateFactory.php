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
             $dummy = new \FabBackend\Object\event($item['id']);
             $dummy->setDataValueObject(new \FabBackend\Object\eventData($item));
             $dummy->setLoaded();
             $dummy->unsetDirty();
             $entities[] = $dummy;
        }
        return new \lw_ddd_entityAggregate($entities);
    }
}
