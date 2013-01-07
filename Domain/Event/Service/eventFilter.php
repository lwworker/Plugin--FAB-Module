<?php

namespace Fab\Domain\Event\Service;

class eventFilter
{
    public function __construct()
    {
    }
    
    public function getInstance()
    {
        return new \Fab\Domain\Event\Service\eventFilter();
    }
    
    public function filter(\LWddd\ValueObject $valueObject)
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
        return new \LWddd\ValueObject($filteredValues);
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
