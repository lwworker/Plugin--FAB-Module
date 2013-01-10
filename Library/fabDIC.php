<?php

namespace Fab\Library;

class fabDIC 
{
    public function __construct()
    {
        
    }
    
    public function getEventQueryHandler()
    {
        if (!$this->eventQueryHandler) {
            $this->eventQueryHandler = new \Fab\Domain\Event\Model\eventQueryHandler($this->getDbObject());
        }
        return $this->eventQueryHandler;        
    }
    
    public function getEventCommandHandler()
    {
        if (!$this->eventCommandHandler) {
            $this->eventCommandHandler = new \Fab\Domain\Event\Model\eventCommandHandler($this->getDbObject());
        }
        return $this->eventCommandHandler;        
    }
    
    public function getDbObject()
    {
        return \lw_registry::getInstance()->getEntry("db");
    }
    
    public function getEventValidationObject()
    {
        $eventValidationSevice = new \Fab\Domain\Event\Service\eventValidate();
        $eventValidationSevice->setQueryHandler($this->getEventQueryHandler());
        return $eventValidationSevice;
    }
    
    public function getEventFilter()
    {
        return \Fab\Domain\Event\Service\eventFilter::getInstance();
    }
    
    public function getEventDecorator()
    {
        return \Fab\Domain\Event\Service\eventDecorator::getInstance();
    }
    
    public function getCountryOptions()
    {
        return new \Fab\Domain\Country\View\countryOptions();
    }
    
}