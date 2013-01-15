<?php

namespace Fab\Domain\Participant\Service;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Participant\Specification\isValid as isValid;

class prepareCsvUpload
{
    public function __construct($domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->dic = new DIC();
        $csvFile = \lw_registry::getInstance()->getEntry('request')->getFileData('csv');
        $this->aggregate = $this->dic->getParticipantRepository()->getParticipantsAggregateByCsvFile($csvFile['tmp_name']);
        $this->eventId = $this->domainEvent->getParameterByKey('eventId');
    }

    public function isInvalid()
    {
         if (count($this->invalid)>0) {
             return true;
         }
         return false;
    }
    
    public function getInvalid()
    {
        return $this->invalid;
    }
    
    public function getAggregate()
    {
        return $this->aggregate;
    }
    
    public function prepare($aggregate)
    {
        $isValidSpecification = isValid::getInstance();
        foreach($this->aggregate as $entity) {
            $i++;
            if (!$isValidSpecification->isSatisfiedBy($entity)) {
                $this->invalid[$i] = array("entity"=>$entity, "errors"=> $isValidSpecification->getErrors());
            }
            elseif ($this->dic->getEventRepository()->getEventIdByEventKey($entity->getValueByKey('v_schluessel')) !== $this->eventId) {
                $this->invalid[$i] = array("entity"=>$entity, "errors"=> 'wrong Event!');
            }
        }
    }
}