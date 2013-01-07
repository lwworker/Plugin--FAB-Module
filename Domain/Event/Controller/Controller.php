<?php

namespace Fab\Domain\Event\Controller;

class Controller extends \LWddd\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
    
    public function showListAction()
    {
        $aggregate = \Fab\Domain\Event\Object\eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, new \Fab\Domain\Event\Model\eventQueryHandler());
        $listView = new \Fab\Domain\Event\View\eventList($aggregate);        
        $this->response->addOutputByName('FabBackend', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $formView = new \Fab\Domain\Event\View\eventForm($this->domainEvent);
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
        $formView = new \Fab\Domain\Event\View\eventForm($this->domainEvent);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    protected function setEntityById($id)
    {
        $event = new \Fab\Domain\Event\Object\event($id);
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
        $PostValueObjectFiltered = \Fab\Domain\Event\Service\eventFilter::getInstance()->filter($this->domainEvent->getPostValueObject());
        $EventValidationSevice = new \Fab\Domain\Event\Service\eventValidate();
        $EventValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $valid = $EventValidationSevice->validate();
        if ($valid)
        {
            try {
                $EventDataValueObject = new \Fab\Domain\Event\Object\eventData($PostValueObjectFiltered->getValues());
            }
            catch (Exception $e) {
                die("error: ".$e->getMessage());
            }
            $entity = new \Fab\Domain\Event\Object\event($id);
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