<?php

namespace Fab\Domain\Event\Object;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Object\eventData as eventData;

class eventAggregateFactory
{
    static public function buildAggregateFromDomainEvent($domainEvent, $queryHandler) 
    {
        $items = array();
        $cmd = $domainEvent->getEventName();
        if ($cmd == "showListAction") {
            $items = $queryHandler->getAllEvents();
        }
        if ($cmd == "showEventListForResponsibleAction") {
            $session = $domainEvent->getSession();
            $items = $queryHandler->loadEventsByResponsible($session->getEmail());
        }
        foreach($items as $item) {
             $dummy = new event($item['id']);
             $dummy->setDataValueObject(new eventData($item));
             $dummy->setLoaded();
             $dummy->unsetDirty();
             $entities[] = $dummy;
        }
        return new \LWddd\EntityAggregate($entities);
    }
}