<?php

namespace Fab\Domain\Event\Service;
use \Fab\Domain\Event\Service\eventDecorator as eventDecorator;
use \LWddd\ValueObject as ValueObject;

class eventDecorator
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
        return new eventDecorator();
    }
    
    public function decorate(ValueObject $valueObject)
    {
        $values = $valueObject->getValues();
        foreach($values as $key => $value){
            $value = trim($value);
            $method = $key.'Decorate';
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $decoratedValues[$key] = $value;
        }
        return new ValueObject($decoratedValues);
    }
    
    public function ansprechpartner_mailDecorate($value)
    {
        if ($this->mailDomain) {
            return str_replace($this->mailDomain, "", $value);
        }
        return $value;
    }
    
    public function stellvertreter_mailDecorate($value)
    {
        if ($this->mailDomain) {
            return str_replace($this->mailDomain, "", $value);
        }
        return $value;
    }
    
    public function anmeldefrist_beginnDecorate($value)
    {
        return $this->baseDateDecorate($value);
    }
    
    public function anmeldefrist_endeDecorate($value)
    {
        return $this->baseDateDecorate($value);
    }
    
    public function v_beginnDecorate($value)
    {
        return $this->baseDateDecorate($value);
    }
    
    public function v_endeDecorate($value)
    {
        return $this->baseDateDecorate($value);
    }
    
    public function baseDateDecorate($value)
    {
        $year = substr($value, 0, 4);
        $month = substr($value, 4, 2); 
        $day   = substr($value, 6, 2);
        $date = $day.".".$month.".".$year;
        return $date;
    }
}