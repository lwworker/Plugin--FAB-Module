<?php

namespace Fab\Domain\Participant\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Participant\Object\participantData as participantData;
use \Exception as Exception;
use \Fab\Library\fabDIC as DIC;

class participant extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
        $this->dic = new DIC();
    }

    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }
    
    public function isDeleteable()
    {
        $this->load();
        return true;
    }
    
    public function delete()
    {
        if ($this->isDeleteable()) {
            return $this->dic->getParticipantCommandHandler()->deleteParticipant($this);
        }
        else {
            throw new Exception('Delete not allowed, because Participant was already submitted to SAP!');
        }
    }

    public function save()
    {
        if ($this->id > 0 ) {
            $result = $this->dic->getParticipantCommandHandler()->saveEntity($this->id, $this->valueObject);
        }
        else {
            $result = $this->dic->getParticipantCommandHandler()->addEntity($this->eventId, $this->valueObject);
            $this->id = $result;
        }
        return $this->finishSaveResult($result);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $data = $this->dic->getParticipantQueryHandler()->getParticipantById($this->id);
            $this->setDataValueObject(new participantData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Participant cannot be loaded, because no ID is present');
        }
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = $this->dic->getParticipantDecorator()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}