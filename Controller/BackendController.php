<?php

namespace FabBackend\Controller;

class BackendController extends \lw_ddd_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
    
    public function showListAction()
    {
        $aggregate = \FabBackend\Object\eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, new \FabBackend\Model\eventQueryHandler());
        $listView = new \FabBackend\View\eventList($aggregate);        
        $this->response->addOutputByName('FabBackend', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $formView = new \FabBackend\View\eventForm($this->domainEvent);
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
        $formView = new \FabBackend\View\eventForm($this->domainEvent);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    protected function setEntity($id)
    {
        $event = new \FabBackend\Object\event($id);
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
    
    protected function saveEvent($id=false)
    {
        $PostValueObjectFiltered = \FabBackend\Service\eventFilter::getInstance()->filter($this->domainEvent->getPostValueObject());
        $EventValidationSevice = new \FabBackend\Service\eventValidate();
        $EventValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $valid = $EventValidationSevice->validate();
        if ($valid)
        {
            try {
                $EventDataValueObject = new \FabBackend\Object\eventData($PostValueObjectFiltered->getValues());
            }
            catch (Exception $e) {
                die("error: ".$e->getMessage());
            }
            $entity = new \FabBackend\Object\event($id);
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