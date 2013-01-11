<?php

namespace Fab\Domain\Replacement\Object;
use \LWddd\ValueObject as ValueObject;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Event\Object\eventFactory as eventFactory;

class replacementData extends ValueObject
{
    public function __construct($values)
    {
        $this->dic = new DIC();
        $allowedKeys = array(
                "id", 
                "stellvertreter_mail");
        
        $event = eventFactory::buildEventByEventId($values['id']);
        $validator = $this->dic->getReplacementValidationObject();
        $validator->setEventEntity($event); 
                
        parent::__construct($values, $allowedKeys, $validator);
    }
}