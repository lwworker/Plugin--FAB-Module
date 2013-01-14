<?php

namespace Fab\Domain\Event\Controller;
use \Fab\Domain\Event\View\replacementForm as replacementForm;
use \Fab\Domain\Event\View\eventDetails as eventDetails;
use \Fab\Domain\Event\Object\eventAggregateFactory as eventAggregateFactory;
use \Fab\Domain\Event\View\eventListForResponsible as eventListForResponsible;
use \Fab\Domain\Event\View\eventList as eventListView;
use \Fab\Domain\Event\View\eventForm as eventFormView;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Fab\Domain\Event\Specification\isValid as isValid;
use \Fab\Domain\Event\Model\eventFactory as eventFactory;
use \Fab\Library\fabDIC as DIC;
use \lw_response as lwResponse;
use \Exception as Exception;

class Controller extends \LWddd\Controller
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "showListAction";
        $this->dic = new DIC();
    }
    
    public function showEventDetailsAction() 
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->domainEvent->setEntity(eventFactory::buildEventByEventId($this->domainEvent->getId()));
        }
        $detailView = new eventDetails($this->domainEvent);
        $this->response->addOutputByName('FabOutput', $detailView->render());
    }
    
    public function showEventListForResponsibleAction() 
    {
        $aggregate = eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, $this->dic->getEventQueryHandler());
        $listView = new eventListForResponsible($this->domainEvent, $aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function showListAction()
    {
        $aggregate = $this->dic->getEventRepository()->getAllEventsAggregate();
        $listView = new eventListView($aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $entity = eventFactory::getInstance()->buildNewEventFromValueObject($this->domainEvent->getDataValueObject());
        $this->domainEvent->setEntity($entity);
        $formView = new eventFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());        
    }
    
    public function showEditFormAction($errors=false)
    {
        if ($errors) {
            $entity = eventFactory::getInstance()->buildNewEventFromValueObject($this->domainEvent->getDataValueObject());
        }
        else {
            $entity = $this->dic->getEventRepository()->getEventObjectById($this->domainEvent->getId());
        }
        $this->domainEvent->setEntity($entity);
        $formView = new eventFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());        
    }
    
    public function saveEventAction()
    {
        $ok = $this->saveEvent($this->domainEvent->getId());
        if ($ok) {
            $this->response->setReloadCmd('showList');
        }
    }
    
    public function addEventAction()
    {
        $ok = $this->saveEvent();
        if ($ok) {
            $this->response->setReloadCmd('showList');
        }
    }
    
    public function deleteEventAction()
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->domainEvent->setEntity(eventFactory::buildEventByEventId($this->domainEvent->getId()));
        }
        $entity = $this->domainEvent->getEntity();
        if ($entity->isDeleteable()) {
            $entity->delete();
            $this->response->setReloadCmd('showList');
        }
        else {
            throw new Exception('delete not possible');
        }
    }
    
    protected function saveEvent($id=false)
    {
        $DataValueObjectFiltered = $this->dic->getEventFilter()->filter($this->domainEvent->getDataValueObject());
        if (!$id) {
            $entity = eventFactory::getInstance()->buildNewEventFromValueObject($DataValueObjectFiltered);
        }
        else {
            $entity = $this->dic->getEventRepository()->getEventObjectById($id);
            $entity->setDataValueObject($DataValueObjectFiltered);
        }
        $isValidSpecification = isValid::getInstance();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            try {
                $result = $this->dic->getEventRepository()->saveEvent($entity);
                if ($result > 0) {
                    return true;
                }
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        else {
            if ($id > 0) {
                $this->showEditFormAction($isValidSpecification->getErrors());
            }
            else {
                $this->showAddFormAction($isValidSpecification->getErrors());
            }
        }
    }
}