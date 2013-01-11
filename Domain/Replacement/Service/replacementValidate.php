<?php

namespace Fab\Domain\Replacement\Service;
use \Fab\Domain\Event\Object\event as event;

class replacementValidate
{
    public function __construct()
    {
        $this->allowedKeys = array("id","stellvertreter_mail");
    }

    public function setValues($array) 
    {
        $this->array = $array;
    }
    
    public function setEventEntity(event $event)
    {
        $this->event = $event;
    }
    
    public function validate()
    {                   
        $valid = true;
        foreach($this->allowedKeys as $key) {
            $function = $key."Validate";
            $result = $this->$function($this->array[$key]);
            if($result == false) {
                $valid = false;
            }
        }
        return $valid;
    }
    
    private function addError($key, $number, $array=false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }
    
    function stellvertreter_mailValidate($value)
    {
        if(empty($value)){ 
            return true;
        }
        else {
            if ($this->event->getValueByKey('ansprechpartner_mail') == $value) {
                $this->addError("stellvertreter_mail", 101, array("errormsg" => "Die Stellvertretermail darf nicht der Ansprechpartnermail entsprechen."));
                $error = true;
            }
            if (!$this->emailValidation("stellvertreter_mail", $value)) {
                $error = true;
            }
            if ($error) {
                return false;
            }
            return true;
        }
    }
   
    function idValidate($key,$value)
    {
        return true;
    }
    
    function emailValidation($key,$value)
    {
        $bool = true;

        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            $this->addError($key, 2, array("errormsg" => "Es wurde keine korrekte E-Mail-Adresse eingegeben."));
            $bool = false;
        }

        if($bool == false) {
            return false;
        }
        return true;
    }
}