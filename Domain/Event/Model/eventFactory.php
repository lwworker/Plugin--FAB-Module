<?php

namespace Fab\Domain\Event\Model;
use \Fab\Domain\Event\Object\event as entity;
use \LWddd\ValueObject as dddValueObject;

class eventFactory
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
            static::$instance = new eventFactory();
        }
        return static::$instance;
    }
    
    public function buildNewEventFromArray($array)
    {
        return $this->buildNewEventFromValueObject(new dddValueObject($array));
    }
    
    public function buildNewEventFromValueObject($object)
    {
        $entity[$this->identifier] = new entity();
        $entity[$this->identifier]->setDataValueObject($object);
        $entity[$this->identifier]->setLoaded();
        $entity[$this->identifier]->setDirty();
        return $entity[$this->identifier];
    }
}