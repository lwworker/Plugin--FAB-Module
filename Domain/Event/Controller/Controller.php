<?php

namespace Fab\Domain\Event\Controller;
use \Fab\Domain\Event\Object\eventAggregateFactory as eventAggregateFactory;
use \Fab\Domain\Event\Model\eventQueryHandler as eventQueryHandler;
use \Fab\Domain\Event\View\eventList as eventList;
use \Fab\Domain\Event\View\eventForm as eventForm;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Service\eventFilter as eventFilter;
use \Fab\Domain\Event\Service\eventValidate as eventValidate;
use \Fab\Domain\Event\Object\eventData as eventData;

class Controller extends \LWddd\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
    
    public function showListAction()
    {
        $aggregate = eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, new eventQueryHandler());
        $listView = new eventList($aggregate);        
        $this->response->addOutputByName('FabBackend', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $formView = new eventForm($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    public function showEditFormAction()
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->setEntityById($this->domainEvent->getId());
        }
        $formView = new eventForm($this->domainEvent);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    protected function setEntityById($id)
    {
        $event = new event($id);
        $event->load();
        $this->domainEvent->setEntity($event);
    }
    
    public function saveEventAction()
    {
        $this->saveEvent($this->domainEvent->getId());
    }
    
    public function addEventAction()
    {
        $this->saveEvent();
    }
    
    public function deleteEventAction()
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->setEntityById($this->domainEvent->getId());
        }
        $entity = $this->domainEvent->getEntity();
        if ($entity->isDeleteable()) {
            $entity->delete();
            die("deleted");
        }
    }
    
    protected function saveEvent($id=false)
    {
        $PostValueObjectFiltered = eventFilter::getInstance()->filter($this->domainEvent->getPostValueObject());
        $EventValidationSevice = new eventValidate();
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
                if ($id > 0) {
                    die("saved");
                }
                else {
                    die("added");
                }
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else {
            $this->showAddFormAction($EventValidationSevice->getErrors());
        }
    }
}