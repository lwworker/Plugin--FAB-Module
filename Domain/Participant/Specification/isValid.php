<?php

namespace Fab\Domain\Participant\Specification;
use \Fab\Domain\Participant\Object\participant as participant;
use \Fab\Library\fabValidation as fabValidation;

class isValid extends fabValidation
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "event_id",
                "anrede",
                "sprache",
                "titel",
                "nachname",
                "vorname",
                "institut",
                "unternehmen",
                "unternehmenshortcut",
                "strasse",
                "plz",
                "ort",
                "land",
                "mail",
                "ust_id_nr",
                "zahlweise",
                "teilnehmer_intern",
                "betrag",
                "first_date",
                "last_date");        
    }
    
    static public function getInstance()
    {
        return new isValid();
    }
    
    protected function setDataArray($array)
    {
        $this->array = $array;
    }
    
    protected function resetErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }
    
    protected function addError($key, $number, $array=false)
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
    
    public function isSatisfiedBy(participant $participant)
    {
        $this->setDataArray($participant->getValues());
        $this->resetErrors();
        
        $valid = true;
        foreach($this->allowedKeys as $key){
            $method = $key."Validate";
            if (method_exists($this, $method)) {
                $result = $this->$method($this->array[$key]);
                if($result == false){
                    $valid = false;
                }
            }
        }
        return $valid;
    }
    
    public function idValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            if(is_numeric($value)) {
                return true;
            }
            else {
                $this->addError("id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    public function event_idValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            if(ctype_digit($value)) {
                return true;
            }
            else {
                $this->addError("event_id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    public function anredeValidate($value)
    {
        return $this->defaultValidation("anrede",$value, 15);
    }
    
    public function spracheValidate($value)
    {
        return $this->defaultValidation("sprache", $value, 2);
    }
    
    public function titelValidate($value)
    {
        return $this->defaultValidation("titel", $value, 20);
    }
    
    public function nachnameValidate($value)
    {
        return $this->defaultValidation("nachname", $value, 35 , true);
    }
    
    public function vornameValidate($value)
    {
        return $this->defaultValidation("vorname", $value, 35 , true);
    }
    
    public function institutValidate($value)
    {
        return $this->defaultValidation("institut", $value, 35);
    }
    
    public function unternehmenValidate($value)
    {
        return $this->defaultValidation("unternehmen", $value, 35);
    }
    
    public function unternehmenShortcutValidate($value)
    {
        return $this->defaultValidation("unternehmenshortcut", $value, 10);
    }
    
    public function straßeValidate($value)
    {
        return $this->defaultValidation("straße", $value, 30);
    }
    
    public function plzValidate($value)
    {
        $zipcheck = new \Fab\Services\Zipcheck\zipcheck();
        if($this->landValidate($this->array["land"])) {
            $ok = $zipcheck->check(strtoupper($this->array["land"]), $value);
            if($ok === 1) {
                return true;
            }
            else {
                $this->addError("plz", 33, array("errormsg" => "PLZ passt nicht zum Land"));
                return false;
            }
        }
        return true;
    }

    public function ortValidate($value)
    {
        return $this->defaultValidation("ort", $value, 35, true);
    }

    public function landValidate($value)
    {
        return $this->defaultValidation("land", $value, 2);
    }

    public function mailValidate($value)
    {
        $bool = $this->requiredValidation("mail", $value);
        if($bool){
            return $this->emailValidation("mail", $value);
        }
        return false;
    }
    
    public function ust_id_nrValidate($value)
    {
        return $this->defaultValidation("ust_id_nr", $value, 20);
    }
    
    public function zahlweiseValidate($value)
    {
        $bool = $this->defaultValidation("zahlweise", $value, 1, true);

        if(!in_array(strtoupper($value), array("K","U"))) {
            $this->addError("zahlweise", 3, array("errormsg" => "unguelte Zahlweisenabkuerzung. ( K = Kreditzahlung, U = Ueberweisung )"));
            $bool = false;
        }
        
        if($bool === false) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function teilnehmer_internValidate($value)
    {
        return $this->defaultValidation("teilnehmer_intern", $value, 1);
    }
    
    public function betragValidate($value)
    {
        return $this->defaultValidation("betrag", $value, 16, true);
    }

    public function first_dateValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            return $this->dateValidation("first_date", $value, true);
        }
    }
    
    public function last_dateValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            return $this->dateValidation("last_date", $value, true);
        }
    }
}