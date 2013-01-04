<?php

namespace FabBackend\Service;

class eventValidate
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
                "stellvertreter_mail",
                "standardbetrag",
                "first_date",
                "last_date");
    }

    public function setValues($array) 
    {
        $this->array = $array;
    }
    
    public function validate()
    {                   
        $valid = true;
        foreach($this->allowedKeys as $key){
            $function = $key."Validate";
            $result = $this->$function($this->array[$key]);
            if($result == false){
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
    
    function idValidate($value){
        if(empty($value)){
            return true;
        }else{
            if(ctype_digit($value)){
                return true;
            }else{
                $this->addError("id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    function buchungskreisValidate($value){
        return $this->defaultValidation("buchungskreis", $value, 4);
    }
    
    function v_schluesselValidate($value){
        return $this->defaultValidation("v_schluessel", $value, 8);
    }
    
    function auftragsnrValidate($value){
        return $this->defaultValidation("auftragsnr", $value, 12);
    }
    
    function bezeichnungValidate($value){
        return $this->defaultValidation("bezeichnung", $value, 50);
    }
    
    function v_landValidate($value){
        return $this->defaultValidation("v_land", $value, 2);
    }
    
    function v_ortValidate($value){
        return $this->defaultValidation("v_ort", $value, 35);
    }
    
    function anmeldefrist_beginnValidate($value){
        return $this->dateValidation("anmeldefrist_beginn", $value);
    }
    
    function anmeldefrist_endeValidate($value){
        return $this->dateValidation("anmeldefrist_ende", $value);
    }
    
    function v_beginnValidate($value){
        return $this->dateValidation("v_beginn", $value);
    }
    
    function v_endeValidate($value){
        return $this->dateValidation("v_ende", $value);
    }
    
    function cpd_kontoValidate($value){
        return $this->defaultValidation("cpd_konto", $value, 10);
    }
    
    function erloeskontoValidate($value){
        return $this->defaultValidation("erloeskonto", $value, 10);
    }
    
    function steuerkennzeichenValidate($value){
        return $this->defaultValidation("steuerkennzeichen", $value, 2);
    }
    
    function steuersatzValidate($value){
        return $this->defaultValidation("steuersatz", $value, 5);
    }
    
    function ansprechpartnerValidate($value){
        return $this->defaultValidation("ansprechpartner", $value, 30);
    }
    
    function ansprechpartner_telValidate($value){
        $bool = true;
        if($value != ""){
            if(!ctype_digit($value)){
                $this->addError("ansprechpartner_tel", 3, array("errormsg" => "Buchstaben in der Tel-Nr. gefunden."));
                $bool = false;
            }
        }
        $bool =  $this->defaultValidation("ansprechpartner_tel", $value, 20);
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    function organisationseinheitValidate($value){
        return $this->defaultValidation("organisationseinheit", $value, 12);
    }
    
    function ansprechpartner_mailValidate($value){
        return $this->emailValidation("ansprechpartner_mail", $value);
    }
    
    function stellvertreter_mailValidate($value){
        return $this->emailValidation("stellvertreter_mail", $value);
    }
    
    function standardbetragValidate($value){
        return $this->defaultValidation("standardbetrag", $value, 16);
    }
    
    function first_dateValidate($value){
        return $this->dateValidation("first_date", $value, true);
    }
    
    function last_dateValidate($value){
        return $this->dateValidation("last_date", $value, true);
    }
    
    function defaultValidation($key,$value,$length){
        $bool = true;
        if($value == ""){
            $this->addError($key, 1, array("errormsg" => "Pflichtfeld ist auszufuellen."));
            $bool = false;
        }
        if(strlen($value) > $length){
            $this->addError($key, 2, array("errormsg" => "Die maximale Zeichenlaenge von ".$length." Zeichen ist einzuhalten."));
            $bool = false;
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    function dateValidation($key, $value, $opt_timecheck = false){
        $bool = true;
        if($value == ""){
            $this->addError($key, 1, array("errormsg" => "Pflichtfeld ist auszufuellen."));
        }
        if($opt_timecheck == true){
            if(strlen($value) != 14){
                $this->addError($key, 2, array("errormsg" => "Datums- + Zeiteingabe nicht korrekt."));
                $bool = false;
            }
        }else{
            if(strlen($value) != 8){
                $this->addError($key, 2, array("errormsg" => "Eingabe nicht korrekt. Es wurden ".  strlen($value) . " Zeichen eingegeben. Das Datumsfeld muss aus 8 Zeichen bestehen YYYYMMDD"));
                $bool = false;
            }
        }
        
        if(strlen($value) >= 8){
            $year = substr($value, 0, 4);
            if($year < date("Y")){
                $this->addError($key, 3, array("errormsg" => "Ungueltiges Jahr, es darf kein vergangenes Jahr eingegeben werden."));
                $bool = false;
            }
        
            $month = substr($value, 4, 2); 
            $day   = substr($value, 6, 2);
            if(!checkdate($month, $day, $year)){
                $this->addError($key, 4, array("errormsg" => "Ungueltiges Datum."));
                $bool = false;
            }
        }
        
        if(strlen($value) == 14){
            if($opt_timecheck == true){
                $hour     = substr($value, 8, 2);
                $min      = substr($value, 10, 2);
                $sec      = substr($value, 12, 2);

                if($hour < 0 | $hour > 23){
                    $this->addError($key, 5, array("errormsg" => "Stunde existiert nicht (nur 00-23 erlaubt)."));
                    $bool = false;
                }

                if($min < 0 | $min > 59){
                    $this->addError($key, 6, array("errormsg" => "Minute existiert nicht (nur 00-59 erlaubt)."));
                    $bool = false;
                }

                if($sec < 0 | $sec > 59){
                    $this->addError($key, 7, array("errormsg" => "Sekunde existiert nicht (nur 00-59 erlaubt)."));
                    $bool = false;
                }
            }
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    function emailValidation($key,$value){
        $bool = true;
        
        if($value == ""){
            $this->addError($key, 1,array("errormsg" => "Pflichtfeld ist auszufuellen."));
            $bool = false;
        }
        
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false){
            $this->addError($key, 2, array("errormsg" => "Es wurde keine korrekte EMail-Adresse eingegeben."));
            $bool = false;
        }

        if($bool == false){
            return false;
        }
        return true;
    }
}