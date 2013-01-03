<?php

class eventFilterService
{
    public function __construct()
    {
        
    }
    
    public function getInstance()
    {
        return new eventFilterService();
    }
    
    public function filter(lw_ddd_valueObject $valueObject)
    {
        $values = $valueObject->getValues();
        $filteredValues['id'] = intval($values['id']);
        $filteredValues['name'] = substr(trim($values['name']), 0, 255);
        return new lw_ddd_valueObject($filteredValues);
    }
}
