<?php

namespace Fab\Domain\Participant\Object;
use \Fab\Domain\Participant\Object\participant as participant;
use \Fab\Domain\Participant\Object\participantData as participantData;

class participantAggregateFactory
{
    static public function buildAggregateFromDomainEvent($domainEvent, $queryHandler) 
    {
        $items = array();
        $cmd = $domainEvent->getEventName();
        $items = $queryHandler->loadParticipantsByEvent($domainEvent->getId());
        foreach($items as $item) {
             $dummy = new participant($item['id']);
             $dummy->setDataValueObject(new participantData($item));
             $dummy->setLoaded();
             $dummy->unsetDirty();
             $entities[] = $dummy;
        }
        return new \LWddd\EntityAggregate($entities);
    }
}