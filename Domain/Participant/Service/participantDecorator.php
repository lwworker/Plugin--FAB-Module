<?php

namespace Fab\Domain\Participant\Service;
use \Fab\Domain\Participant\Service\participantDecorator as participantDecorator;
use \LWddd\ValueObject as ValueObject;

class participantDecorator
{
    public function __construct()
    {
    }
    
    public function setDefaultMailDomain($mailDomain)
    {
        $this->mailDomain = $mailDomain;
    }
    
    public function getInstance()
    {
        return new participantDecorator();
    }
    
    public function decorate(ValueObject $valueObject)
    {
        $values = $valueObject->getValues();
        foreach($values as $key => $value){
            $value = trim($value);
            $method = $key.'Decorate';
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $decoratedValues[$key] = $value;
        }
        return new ValueObject($decoratedValues);
    }
}