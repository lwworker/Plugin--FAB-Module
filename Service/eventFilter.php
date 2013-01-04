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
        foreach($values as $key => $value) {
            $value = trim($value);
            $method = $key.'Filter';
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $filteredValues[$key] = $value;
        }
        return new \lw_ddd_valueObject($filteredValues);
    }
    
    public function ansprechpartner_mailFilter($value)
    {
        return $value.'@fz-juelich.de';
    }
    
    public function anmeldefrist_beginnFilter($value)
    {
        return $this->baseDateFilter($value);
    }
    
    public function anmeldefrist_endeFilter($value)
    {
        return $this->baseDateFilter($value);
    }
    
    public function v_beginnFilter($value)
    {
        return $this->baseDateFilter($value);
    }
    
    public function v_endeFilter($value)
    {
        return $this->baseDateFilter($value);
    }
    
    public function baseDateFilter($value)
    {
        $parts = explode(".", $value);
        return $parts[2].str_pad($parts[1], 2, '0', STR_PAD_LEFT).str_pad($parts[0], 2, '0', STR_PAD_LEFT);
    }
}