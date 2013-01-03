<?php

namespace FabBackend\Service;

class eventValidate
{
    public function __construct()
    {
    }
            // if ($entity->isValid()) {...}

    public function setValues($array) 
    {
        $this->array = $array;
    }
    
    public function validate()
    {
        $valid = true;
        if (strlen($this->array['bezeichnung'])<3) {
            $this->addError(1);
            $valid = false;
        }
        if (strlen($this->array['buchungskreis'])<3) {
            $this->addError(2);
            $valid = false;
        }
        return $valid;
    }
    
    private function addError($number)
    {
        $this->errors[] = $number;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
