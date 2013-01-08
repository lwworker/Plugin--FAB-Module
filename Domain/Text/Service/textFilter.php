<?php

namespace Fab\Domain\Text\Service;
use \Fab\Domain\Text\Service\textFilter as textFilter;
use \LWddd\ValueObject as ValueObject;

class textFilter
{
    public function __construct()
    {
    }
    
    public function getInstance()
    {
        return new textFilter();
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
