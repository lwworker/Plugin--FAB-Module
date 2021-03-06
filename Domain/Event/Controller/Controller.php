<?php

namespace Fab\Domain\Event\Controller;
use \Fab\Domain\Event\View\replacementForm as replacementForm;
use \Fab\Domain\Event\View\eventDetails as eventDetailsView;
use \Fab\Domain\Event\Object\eventAggregateFactory as eventAggregateFactory;
use \Fab\Domain\Event\View\eventListForResponsible as eventListForResponsible;
use \Fab\Domain\Event\View\eventList as eventListView;
use \Fab\Domain\Event\View\eventForm as eventFormView;
use \Fab\Domain\Event\Object\event as event;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Fab\Domain\Event\Specification\validationErrorsException as validationErrorsException;
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
            $this->domainEvent->setEntity($this->dic->getEventRepository()->getEventObjectById($this->domainEvent->getId()));
        }
        $detailView = new eventDetailsView($this->domainEvent);
        $this->response->addOutputByName('FabOutput', $detailView->render());
    }
    
    public function showEventListForResponsibleAction() 
    {
        $aggregate = $this->dic->getEventRepository()->getEventsForResponsibleAggregate($this->session->getEmail());
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
        $formView->setErrors($errors);
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
        $formView->setErrors($errors);
        $this->response->addOutputByName('FabOutput', $formView->render());        
    }
    
    public function saveEventAction()
    {
        try {
            $result = $this->dic->getEventRepository()->saveEvent($this->domainEvent->getId(), $this->domainEvent->getDataValueObject());
            $this->response->setReloadCmd('showList');
        }
        catch (validationErrorsException $e) {
            $this->showEditFormAction($e->getErrors());
        }        
    }
    
    public function addEventAction()
    {
        try {
            $result = $this->dic->getEventRepository()->saveEvent(false, $this->domainEvent->getDataValueObject());
            $this->response->setReloadCmd('showList');
        }
        catch (validationErrorsException $e) {
            $this->showAddFormAction($e->getErrors());
        }        
    }
    
    public function deleteEventAction()
    {
        try {
            $repository = $this->dic->getEventRepository();
            $repository->setParticipantRepository($this->dic->getParticipantRepository());
            $ok = $repository->deleteEventById($this->domainEvent->getId());
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }        
        $this->response->setReloadCmd('showList');
    }
}