<?php

namespace Fab\Domain\Text\Service;
use \Fab\Domain\Text\Service\textDecorator as textDecorator;
use \LWddd\ValueObject as ValueObject;

class textDecorator
{
    public function __construct()
    {
        
    }
    
    public function getInstance()
    {
        return new textDecorator();
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