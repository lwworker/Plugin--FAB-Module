<?php

namespace Fab\Domain\Replacement\Service;
use \Fab\Domain\Replacement\Service\replacementFilter as replacementFilter;
use \LWddd\ValueObject as ValueObject;

class replacementFilter
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
        return new replacementFilter();
    }
    
    public function filter(ValueObject $valueObject)
    {
        $values = $valueObject->getValues();
        foreach($values as $key => $value) {
            $value = trim($value);
            
            if ($key == "stellvertreter_mail" && strlen(trim($value))>0 && $this->mailDomain) {
                $value = $value.$this->mailDomain;
            }            
            
            $filteredValues[$key] = $value;
        }
        return new ValueObject($filteredValues);
    }    
}
