<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabRepository as fabRepository;
use \Fab\Domain\Participant\Object\participant as participant;
use \LWddd\ValueObject as ValueObject;
use \Fab\Domain\Participant\Specification\isDeletable as isDeletable;

class participantRepository extends fabRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $this->commandHandler = new participantCommandHandler($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $this->queryHandler = new participantQueryHandler($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
    
    protected function buildParticipantObjectByArray($data)
    {
        $participant = new participant($data['id']);
        $participant->setDataValueObject(new ValueObject($data));
        $participant->setLoaded();
        $participant->unsetDirty();
        return $participant;
    }
    
    public function getParticipantObjectById($id)
    {
        $data = $this->getQueryHandler()->loadParticipantById($id);
        return $this->buildParticipantObjectByArray($data);
    }
    
    public function getParticipantsAggregateByEventId($eventId)
    {
        $items = $this->getQueryHandler()->loadParticipantsByEventId($eventId);
        foreach($items as $item) {
             $entities[] =  $this->buildParticipantObjectByArray($item);
        }
        return new \LWddd\EntityAggregate($entities);
    }
    
    public function saveParticipant($eventId, participant $participant)
    {
        if ($participant->getId() > 0 ) {
            $result = $this->getCommandHandler()->saveEntity($participant->getId(), $participant->getValues());
            $id = $participant->getId();
        }
        else {
            $result = $this->getCommandHandler()->addEntity($eventId, $participant->getValues());
            $id = $result;
        }
        if ($result) {
            $participant->setLoaded();
            $participant->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $participant->setLoaded();
            }
            else {
                $participant->unsetLoaded();
            }
            $participant->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }
        return $id;
    }
    
    public function deleteParticipantById($id)
    {
        $participant = $this->getParticipantObjectById($id);
        if (isDeletable::getInstance()->isSatisfiedBy($participant)) {
            return $this->getCommandHandler()->deleteEntityById($id);
        }
        else {
            throw new Exception('Delete not allowed, because Participant was already submitted to SAP!');
        }
    }
}