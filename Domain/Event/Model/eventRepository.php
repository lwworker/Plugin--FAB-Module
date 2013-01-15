<?php

namespace Fab\Domain\Event\Model;
use \Fab\Library\fabRepository as fabRepository;
use \Fab\Domain\Event\Object\event as event;
use \LWddd\ValueObject as ValueObject;
use \Fab\Domain\Event\Specification\isDeletable as isDeletable;
use \Fab\Domain\Event\Specification\isValid as isValid;

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
    
    protected function prepareObjectToSave($id, $dataObject) 
    {
        $DataValueObjectFiltered = $this->dic->getEventFilter()->filter($dataObject);
        if (!$id) {
            $entity = eventFactory::getInstance()->buildNewEventFromValueObject($DataValueObjectFiltered);
        }
        else {
            $entity = $this->dic->getEventRepository()->getEventObjectById($id);
            $entity->setDataValueObject($DataValueObjectFiltered);
        }
        return $entity;
    }
    
    public function saveEvent($id, $dataObject)
    {
        $entity = $this->prepareObjectToSave($id, $dataObject);
        $isValidSpecification = isValid::getInstance();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            if ($entity->getId() > 0 ) {
                $result = $this->getCommandHandler()->saveEntity($entity->getId(), $entity->getValues());
                $id = $entity->getId();
            }
            else {
                $result = $this->getCommandHandler()->addEntity($entity->getValues());
                $id = $result;
            }
            if ($result) {
                $entity->setLoaded();
                $entity->unsetDirty();
            }
            else {
                if ($id > 0 ) {
                    $entity->setLoaded();
                }
                else {
                    $entity->unsetLoaded();
                }
                $entity->setDirty();
                throw new Exception('An DB Error occured saving the Entity');
            }
            return $id;
        }
        else {
            $exception = new \Fab\Domain\Event\Specification\validationErrorsException('Error');
            $exception->setErrors($isValidSpecification->getErrors());
            throw $exception;
        }
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
    
    public function getEventIdByEventKey($key)
    {
         return $this->getQueryHandler()->getEventIdByEventKey($key);
    }
}