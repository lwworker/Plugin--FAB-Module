<?php

namespace Fab\Domain\Event\Controller;
use \Fab\Domain\Event\Object\eventAggregateFactory as eventAggregateFactory;
use \Fab\Domain\Event\Model\eventQueryHandler as eventQueryHandler;
use \Fab\Domain\Event\Model\eventCommandHandler as eventCommandHandler;
use \Fab\Domain\Event\View\eventList as eventList;
use \Fab\Domain\Event\View\eventListForResponsible as eventListForResponsible;
use \Fab\Domain\Event\View\eventDetails as eventDetails;
use \Fab\Domain\Event\View\eventForm as eventForm;
use \Fab\Domain\Event\View\replacementForm as replacementForm;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Service\eventFilter as eventFilter;
use \Fab\Domain\Event\Service\eventValidate as eventValidate;
use \Fab\Domain\Event\Object\eventData as eventData;
use \lw_response as lwResponse;
use \lw_registry as lwRegistry;
use \Exception as Exception;

class Controller extends \LWddd\Controller
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "showListAction";
    }
    
    public function saveReplacementAction()
    {
        $PostValueObjectFiltered = eventFilter::getInstance()->filter($this->domainEvent->getPostValueObject());
        $EventValidationSevice = new eventValidate();
        $valid = $EventValidationSevice->stellvertreter_mailValidate($PostValueObjectFiltered->getValueByKey('stellvertreter_mail'), $this->domainEvent->getId());
        if ($valid)
        {
            $eventCommandHandler = new eventCommandHandler(lwRegistry::getInstance()->getEntry("db"));
            $ok = $eventCommandHandler->saveReplacement($this->domainEvent->getId(), $PostValueObjectFiltered->getValueByKey('stellvertreter_mail'));
            if ($ok > 0) {
                $this->response->setReloadCmd('showEventDetails', array("id"=>$this->domainEvent->getId()));
            }
            else {
                throw new Exception('error saving the replacement');
            }
        }
        else {
            $this->showReplacementFormAction($EventValidationSevice->getErrors());
        }        
    }    
    
    public function showReplacementFormAction($errors=false)
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->setEntityById($this->domainEvent->getId());
        }
        $formView = new replacementForm($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }        
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function showEventDetailsAction() 
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->setEntityById($this->domainEvent->getId());
        }
        $detailView = new eventDetails($this->domainEvent);
        $this->response->addOutputByName('FabOutput', $detailView->render());
    }
    
    public function showEventListForResponsibleAction() 
    {
        $aggregate = eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, new eventQueryHandler(lwRegistry::getInstance()->getEntry("db")));
        $listView = new eventListForResponsible($this->domainEvent, $aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function showListAction()
    {
        $aggregate = eventAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, new eventQueryHandler(lwRegistry::getInstance()->getEntry("db")));
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
            $this->setEntityById($this->domainEvent->getId());
        }
        $formView = new eventForm($this->domainEvent);
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function saveEventAction()
    {
        $ok = $this->saveEvent($this->domainEvent->getId());
        if ($ok) {
            $this->response->setReloadCmd('showList');
        }
        else {
            throw new Exception('error saving the event');
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
            $this->setEntityById($this->domainEvent->getId());
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
    
    protected function setEntityById($id)
    {
        $event = new event($id);
        $event->load();
        $this->domainEvent->setEntity($event);
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