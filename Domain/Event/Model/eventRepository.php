<?php

namespace Fab\Domain\Event\Model;
use \Fab\Library\fabRepository as fabRepository;
use \Fab\Domain\Event\Object\event as event;
use \LWddd\ValueObject as ValueObject;
use \Fab\Domain\Event\Specification\isDeletable as isDeletable;

class eventRepository extends fabRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $this->commandHandler = new eventCommandHandler($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $this->queryHandler = new eventQueryHandler($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
    
    protected function buildAggregateFromQueryResult($items)
    {
        foreach($items as $item) {
             $entities[] =  $this->buildEventObjectByArray($item);
        }
        return new \LWddd\EntityAggregate($entities);
    }
    
    public function getEventsForResponsibleAggregate($ansprechpartner_mail)
    {
        $items = $this->getQueryHandler()->loadEventsByResponsible($ansprechpartner_mail);
        return $this->buildAggregateFromQueryResult($items);
    }
    
    public function getAllEventsAggregate()
    {
        $items = $this->getQueryHandler()->loadAllEvents();
        return $this->buildAggregateFromQueryResult($items);
    }
    
    public function buildEventObjectByArray($data)
    {
        $event = new event($data['id']);
        $event->setDataValueObject(new ValueObject($data));
        $event->setLoaded();
        $event->unsetDirty();
        return $event;
    }
    
    public function getEventObjectById($id)
    {
        $data = $this->getQueryHandler()->loadEventById($id);
        return $this->buildEventObjectByArray($data);
    }
    
    public function saveEvent(event $event)
    {
        if ($event->getId() > 0 ) {
            $result = $this->getCommandHandler()->saveEntity($event->getId(), $event->getValues());
            $id = $event->getId();
        }
        else {
            $result = $this->getCommandHandler()->addEntity($event->getValues());
            $id = $result;
        }
        if ($result) {
            $event->setLoaded();
            $event->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $event->setLoaded();
            }
            else {
                $event->unsetLoaded();
            }
            $event->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }
        return $id;
    }
    
    public function setParticipantRepository($repository)
    {
        $this->participantRepository = $repository;
    }
    
    public function deleteEventById($id)
    {
        $event = $this->getEventObjectById($id);
        if (isDeletable::getInstance()->isSatisfiedBy($event)) {
            $ok = $this->participantRepository->deleteAllParticipantsByEventIdAndOverrideIsDeletableSpecification($id);
            if (!$ok) {
                throw new Exception('Delete not allowed, because Participant was already submitted to SAP!');
            }
            return $this->getCommandHandler()->deleteEntityById($id);
        }
        else {
            throw new Exception('Delete not allowed, because Participant was already submitted to SAP!');
        }
    }
}