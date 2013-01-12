<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Domain\Participant\Object\participant as entity;
use \LWddd\ValueObject as dddValueObject;

class participantFactory
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
            static::$instance = new participantFactory();
        }
        return static::$instance;
    }
    
    public function buildNewParticipantFromArray($array)
    {
        return $this->buildNewParticipantFromValueObject(new dddValueObject($array));
    }
    
    public function buildNewParticipantFromValueObject($object)
    {
        $entity[$this->identifier] = new entity();
        $entity[$this->identifier]->setDataValueObject($object);
        $entity[$this->identifier]->setLoaded();
        $entity[$this->identifier]->setDirty();
        return $entity[$this->identifier];
    }
}