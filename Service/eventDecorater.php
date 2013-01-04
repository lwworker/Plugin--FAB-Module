<?php

namespace FabBackend\Service;

class eventDecorater
{
    public function __construct()
    {
        
    }
    
    public function getInstance()
    {
        return new \FabBackend\Service\eventDecorator();
    }
    
    public function decorate(\lw_ddd_valueObject $valueObject)
    {
        $values = $valueObject->getValues();
        return new \lw_ddd_valueObject($filteredValues);
    }
}