<?php

namespace Fab\Domain\Event\Object;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Event\Object\eventData as eventData;

class eventFactory 
{
    public static function buildEventByEventId($id)
    {
        $dic = new DIC();        
        $data = $dic->getEventQueryHandler()->getEventById($id);
        $event = new event();
        $event->setDataValueObject(new eventData($data));
        $event->setLoaded();
        $event->unsetDirty();
        return $event;
    }
}