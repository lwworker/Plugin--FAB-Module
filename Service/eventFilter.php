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
        $filteredValues['buchungskreis'] = substr(trim($values['buchungskreis']), 0, 255);
        $filteredValues['bezeichnung'] = substr(trim($values['bezeichnung']), 0, 255);
        return new \lw_ddd_valueObject($filteredValues);
    }
}