<?php

namespace Fab\Domain\Event\Controller;
use \Fab\Domain\Event\View\replacementForm as replacementForm;
use \Fab\Domain\Event\View\eventDetails as eventDetails;
use \Fab\Domain\Event\Object\eventAggregateFactory as eventAggregateFactory;
use \Fab\Domain\Event\View\eventListForResponsible as eventListForResponsible;
use \Fab\Domain\Event\View\eventList as eventList;
use \Fab\Domain\Event\View\eventForm as eventForm;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Fab\Domain\Event\Object\eventFactory as eventFactory;
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
        $aggregate = eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, $this->dic->getEventQueryHandler());
        $listView = new eventList($aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $formView = new eventForm($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function showEditFormAction($errors=false)
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->domainEvent->setEntity(eventFactory::buildEventByEventId($this->domainEvent->getId()));
        }
        $formView = new eventForm($this->domainEvent);
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
        $PostValueObjectFiltered = $this->dic->getEventFilter()->filter($this->domainEvent->getPostValueObject());
        $EventValidationSevice = $this->dic->getEventValidationObject(); 
        $EventValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $valid = $EventValidationSevice->validate();
        if ($valid)
        {
            try {
                $EventDataValueObject = new eventData($PostValueObjectFiltered->getValues());
            }
            catch (Exception $e) {
                die("error: ".$e->getMessage());
            }
            $entity = new event($id);
            $entity->setDataValueObject($EventDataValueObject);
            try {
                $result = $entity->save();
                if ($result > 0) {
                    return true;
                }
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else {
            if ($id > 0) {
                $this->showEditFormAction($EventValidationSevice->getErrors());
            }
            else {
                $this->showAddFormAction($EventValidationSevice->getErrors());
            }
        }
    }
}