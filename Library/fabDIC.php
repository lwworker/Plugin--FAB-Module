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
    
    public function getParticipantRepository()
    {
        if (!$this->participantRepository) {
            $this->participantRepository = new \Fab\Domain\Participant\Model\participantRepository();
        }
        return $this->participantRepository;        
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
    
    public function getEventRepository()
    {
        if (!$this->eventRepository) {
            $this->eventRepository = new \Fab\Domain\Event\Model\eventRepository();
        }
        return $this->eventRepository;        
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