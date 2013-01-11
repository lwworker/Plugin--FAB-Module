<?php

namespace Fab\Library;

class fabDIC 
{
    public function __construct()
    {
        
    }
    
    public function getReplacementQueryHandler()
    {
        if (!$this->replacementQueryHandler) {
            $this->replacementQueryHandler = new \Fab\Domain\Replacement\Model\replacementQueryHandler($this->getDbObject());
        }
        return $this->replacementQueryHandler;        
    }
    
    public function getReplacementCommandHandler()
    {
        if (!$this->replacementCommandHandler) {
            $this->replacementCommandHandler = new \Fab\Domain\Replacement\Model\replacementCommandHandler($this->getDbObject());
        }
        return $this->replacementCommandHandler;        
    }
    
    public function getReplacementValidationObject()
    {
        return new \Fab\Domain\Replacement\Service\replacementValidate();
    }
    
    public function getReplacementDecorator()
    {
        $config = $this->getConfiguration();
        $decorator = \Fab\Domain\Replacement\Service\replacementDecorator::getInstance();
        if ($config['fab']['defaultMailDomain']) {
            $decorator->setDefaultMailDomain($config['fab']['defaultMailDomain']);
        }
        return $decorator;
    }
    
    public function getReplacementFilter()
    {
        $config = $this->getConfiguration();
        $filter = \Fab\Domain\Replacement\Service\replacementFilter::getInstance();
        if ($config['fab']['defaultMailDomain']) {
            $filter->setDefaultMailDomain($config['fab']['defaultMailDomain']);
        }
        return $filter;
    }    
    
    public function getParticipantQueryHandler()
    {
        if (!$this->participantQueryHandler) {
            $this->participantQueryHandler = new \Fab\Domain\Participant\Model\participantQueryHandler($this->getDbObject());
        }
        return $this->participantQueryHandler;        
    }
    
    public function getParticipantCommandHandler()
    {
        if (!$this->participantCommandHandler) {
            $this->participantCommandHandler = new \Fab\Domain\Participant\Model\participantCommandHandler($this->getDbObject());
        }
        return $this->participantCommandHandler;        
    }
    
    public function getParticipantFilter()
    {
        $config = $this->getConfiguration();
        $filter = \Fab\Domain\Participant\Service\participantFilter::getInstance();
        return $filter;
    }
    
    public function getParticipantDecorator()
    {
        $config = $this->getConfiguration();
        $decorator = \Fab\Domain\Participant\Service\participantDecorator::getInstance();
        return $decorator;
    }
    
    public function getParticipantValidationObject()
    {
        $participantValidationSevice = new \Fab\Domain\Participant\Service\participantValidate();
        return $participantValidationSevice;
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
    
    public function getEventValidationObject()
    {
        $eventValidationSevice = new \Fab\Domain\Event\Service\eventValidate();
        $eventValidationSevice->setQueryHandler($this->getEventQueryHandler());
        return $eventValidationSevice;
    }
    
    public function getEventFilter()
    {
        $config = $this->getConfiguration();
        $filter = \Fab\Domain\Event\Service\eventFilter::getInstance();
        if ($config['fab']['defaultMailDomain']) {
            $filter->setDefaultMailDomain($config['fab']['defaultMailDomain']);
        }
        return $filter;
    }
    
    public function getEventDecorator()
    {
        $config = $this->getConfiguration();
        $decorator = \Fab\Domain\Event\Service\eventDecorator::getInstance();
        if ($config['fab']['defaultMailDomain']) {
            $decorator->setDefaultMailDomain($config['fab']['defaultMailDomain']);
        }
        return $decorator;
    }
    
    public function getDbObject()
    {
        return \lw_registry::getInstance()->getEntry("db");
    }
    
    public function getCountryOptions()
    {
        return new \Fab\Domain\Country\View\countryOptions();
    }
    
    public function getConfiguration()
    {
        return \lw_registry::getInstance()->getEntry("config");
    }
}