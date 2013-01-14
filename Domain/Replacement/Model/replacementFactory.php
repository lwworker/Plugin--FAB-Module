<?php

namespace Fab\Domain\Replacement\Model;
use \Fab\Domain\Replacement\Object\replacement as entity;
use \LWddd\ValueObject as dddValueObject;

class replacementFactory
{
    static private $identifier = 0;
    static private $instance = false;
    
    protected function __construct() 
    {
        $this->identifier++;
    }
    
    static public function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new replacementFactory();
        }
        return static::$instance;
    }
    
    public function buildNewEventReplacementArray($array)
    {
        return $this->buildNewReplacementFromValueObject(new dddValueObject($array));
    }
    
    public function buildNewReplacementFromValueObject($object)
    {
        $entity[$this->identifier] = new entity();
        $entity[$this->identifier]->setDataValueObject($object);
        $entity[$this->identifier]->setLoaded();
        $entity[$this->identifier]->setDirty();
        return $entity[$this->identifier];
    }
}