<?php

namespace Fab\Library;

class fabValidation
{
    public function __construct()
    {
        
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
    
    public function emailValidation($key,$value)
    {
        $bool = true;

        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false){
            $this->addError($key, 2, array("errormsg" => "Es wurde keine korrekte EMail-Adresse eingegeben."));
            $bool = false;
        }

        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function requiredValidation($key, $value)
    {
        if($value == "") {
            $this->addError($key, 1, array("errormsg" => "Pflichtfeld ist auszufuellen."));
            return false;
        }
        return true;
    }
    
    public function requiredDateValidation($key, $value)
    {
        $bool = true;
        $bool = $this->requiredValidation($key, $value);
        $bool = $this->dateValidation($key, $value);
        
        if($bool == false) {
            return false;
        }
        return true;
    }    
    
    public function dateValidation($key, $value, $opt_timecheck = false)
    {
        $bool = true;
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
    
    public function defaultValidation($key,$value,$length,$required = false)
    {
        $bool = true;
        
        if($required === true) {
            $bool = $this->requiredValidation($key, $value);
        }
        $bool = $this->maxLengthValidation($key, $value, $length);
        
        if($bool == false) {
            return false;
        }
        return true;
    }
    
    public function maxLengthValidation($key, $value, $length)
    {
        if(strlen($value) > $length) {
            $this->addError($key, 2, array("errormsg" => "Die maximale Zeichenlaenge von ".$length." Zeichen ist einzuhalten."));
            return false;
        }
        return true;
    }
}