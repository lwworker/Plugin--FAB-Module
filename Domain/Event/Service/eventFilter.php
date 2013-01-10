<?php

namespace Fab\Domain\Event\Service;
use \Fab\Domain\Event\Service\eventFilter as eventFilter;
use \LWddd\ValueObject as ValueObject;

class eventFilter
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
        return new eventFilter();
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
    
    public function ansprechpartner_mailFilter($value)
    {
        if ($this->mailDomain) {
            return $value.$this->mailDomain;
        }
        return $value;
    }
    
    public function stellvertreter_mailFilter($value)
    {
        if (strlen(trim($value))>0 && $this->mailDomain) {
            return $value.$this->mailDomain;
        }
        return false;
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
