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
    
    public function showAddFormAction()
    {
        $formView = new eventFormView($this->domainEvent);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    public function addEntityAction()
    {
        $filteredValueObject = eventFilterService::getInstance()->filter($this->domainEvent->getValueObject());
        $entity = new eventObject($filteredValueObject);
        $entity->setValidateService(new eventValidateService());
        $entity->validate();
        $this->domainEvent->setEntity($entity);
        if ($entity->isValid())
        {
            try {
                $this->commandBus->register('addEntityAction', new eventCommandHandlerModel());
                $this->commandBus->handle($this->domainEvent);
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else {
            $this->showAddFormAction();
        }
    }
}
