<?php

namespace Fab\Domain\Participant\Service;
use \Fab\Domain\Participant\Service\participantFilter as participantFilter;
use \LWddd\ValueObject as ValueObject;

class participantFilter
{
    public function __construct()
    {
    }
    
    public function getInstance()
    {
        return new participantFilter();
    }
    
    public function filter(ValueObject $valueObject)
    {
        $values = $valueObject->getValues();
        foreach($values as $key => $value) {
            $value = trim($value);
            $method = $key.'Filter';
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $filteredValues[$key] = $value;
        }
        return new ValueObject($filteredValues);
    }
}