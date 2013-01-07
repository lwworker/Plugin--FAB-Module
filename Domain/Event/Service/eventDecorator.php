<?php

namespace Fab\Domain\Event\Service;

class eventDecorator
{
    public function __construct()
    {
        
    }
    
    public function getInstance()
    {
        return new \Fab\Domain\Event\Service\eventDecorator();
    }
    
    public function decorate(\LWddd\ValueObject $valueObject)
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
        return new \LWddd\ValueObject($decoratedValues);
    }
    
    public function ansprechpartner_mailDecorate($value)
    {
        return str_replace('@fz-juelich.de', "", $value);
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