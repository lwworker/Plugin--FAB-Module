<?php

namespace Fab\Domain\Event\Specification;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Library\fabValidation as fabValidation;

class isValid extends fabValidation
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "buchungskreis",
                "v_schluessel",
                "auftragsnr",
                "bezeichnung",
                "v_land",
                "v_ort",
                "anmeldefrist_beginn",
                "anmeldefrist_ende",
                "v_beginn",
                "v_ende",
                "cpd_konto",
                "erloeskonto",
                "steuerkennzeichen",
                "steuersatz",
                "ansprechpartner",
                "ansprechpartner_tel",
                "organisationseinheit",
                "ansprechpartner_mail",
                "standardbetrag",
                "first_date",
                "last_date");        
    }
    
    static public function getInstance()
    {
        return new isValid();
    }
    
    public function isSatisfiedBy(event $event)
    {
        $this->setDataArray($event->getValues());
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
    
    function idValidate($value) 
    {
        if(empty($value)) {
            return true;
        }
        else {
            if(ctype_digit($value)) {
                return true;
            }
            else {
                $this->addError("id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    function buchungskreisValidate($value)
    {
        return $this->defaultValidation("buchungskreis", $value, 4, true);
    }
    
    function v_schluesselValidate($value)
    {
        return $this->defaultValidation("v_schluessel", $value, 8, true);
    }
    
    function auftragsnrValidate($value)
    {
        return $this->defaultValidation("auftragsnr", $value, 12, true);
    }
    
    function bezeichnungValidate($value)
    {
        return $this->defaultValidation("bezeichnung", $value, 50, true);
    }
    
    function v_landValidate($value)
    {
        return $this->defaultValidation("v_land", $value, 2, true);
    }
    
    function v_ortValidate($value)
    {
        return $this->defaultValidation("v_ort", $value, 35, true);
    }
    
    function anmeldefrist_beginnValidate($value)
    {
        return $this->requiredDateValidation("anmeldefrist_beginn", $value);
    }
    
    function anmeldefrist_endeValidate($value)
    {
        return $this->requiredDateValidation("anmeldefrist_ende", $value);
    }
    
    function v_beginnValidate($value)
    {
        return $this->requiredDateValidation("v_beginn", $value);
    }
    
    function v_endeValidate($value)
    {
        return $this->requiredDateValidation("v_ende", $value);
    }
    
    function cpd_kontoValidate($value)
    {
        return $this->defaultValidation("cpd_konto", $value, 10, true);
    }
    
    function erloeskontoValidate($value)
    {
        return $this->defaultValidation("erloeskonto", $value, 10, true);
    }
    
    function steuerkennzeichenValidate($value)
    {
        return $this->defaultValidation("steuerkennzeichen", $value, 2, true);
    }
    
    function steuersatzValidate($value)
    {
        return $this->defaultValidation("steuersatz", $value, 5, true);
    }
    
    function ansprechpartnerValidate($value)
    {
        return $this->defaultValidation("ansprechpartner", $value, 30, true);
    }
    
    function ansprechpartner_telValidate($value)
    {
        return $this->defaultValidation("ansprechpartner_tel", $value, 20, true);
    }
    
    function organisationseinheitValidate($value)
    {
        return $this->defaultValidation("organisationseinheit", $value, 12, true);
    }
    
    function ansprechpartner_mailValidate($value)
    {
        $bool = $this->requiredValidation("ansprechpartner_mail", $value);
        if($bool) {
            return $this->emailValidation("ansprechpartner_mail", $value);
        }
        return false;
    }
    
    function standardbetragValidate($value)
    {
        return $this->defaultValidation("standardbetrag", $value, 16, true);
    }
    
    function first_dateValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            return $this->dateValidation("first_date", $value, true);
        }
    }
    
    function last_dateValidate($value)
    {
        if(empty($value)) {
            return true;
        }
        else {
            return $this->dateValidation("last_date", $value, true);
        }
    }
}