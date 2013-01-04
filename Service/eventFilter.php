<?php

namespace FabBackend\Service;

class eventFilter
{
    public function __construct()
    {
        
    }
    
    public function getInstance()
    {
        return new \FabBackend\Service\eventFilter();
    }
    
    public function filter(\lw_ddd_valueObject $valueObject)
    {
        $values = $valueObject->getValues();
        $filteredValues['id'] = intval($values['id']);
        $filteredValues['buchungskreis'] = trim($values['buchungskreis']);
        $filteredValues['bezeichnung'] = trim($values['bezeichnung']);
        return new \lw_ddd_valueObject($filteredValues);
    }
}