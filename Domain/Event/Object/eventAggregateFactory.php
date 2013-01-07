<?php

namespace Fab\Domain\Event\Object;

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
             $dummy = new \Fab\Domain\Event\Object\event($item['id']);
             $dummy->setDataValueObject(new \Fab\Domain\Event\Object\eventData($item));
             $dummy->setLoaded();
             $dummy->unsetDirty();
             $entities[] = $dummy;
        }
        return new \lw_ddd_entityAggregate($entities);
    }
}
