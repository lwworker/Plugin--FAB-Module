<?php

namespace Fab\Domain\Replacement\Specification;
use \Fab\Domain\Replacement\Object\replacement as replacement;
use \Fab\Library\fabValidation as fabValidation;
use \Fab\Domain\Event\Object\event as event;

class isValid extends fabValidation
{
    public function __construct()
    {
        $this->allowedKeys = array("id","stellvertreter_mail");       
    }
    
    static public function getInstance()
    {
        return new isValid();
    }
    
    public function setEvent(event $event)
    {
        $this->event = $event;
    }
    
    public function isSatisfiedBy(replacement $replacement)
    {
        $this->setDataArray($replacement->getValues());
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
}