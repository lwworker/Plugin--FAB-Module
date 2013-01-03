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
        $formView = new \FabBackend\View\eventForm($this->domainEvent);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    public function addEventAction()
    {
        $filteredValueObject = \FabBackend\Service\eventFilter::getInstance()->filter($this->domainEvent->getValueObject());
        $entity = new \FabBackend\Object\event($filteredValueObject);
        $entity->setValidateService(new \FabBackend\Service\eventValidate());
        $entity->validate();
        $this->domainEvent->setEntity($entity);
        if ($entity->isValid())
        {
            try {
                $this->commandBus->register('addEventAction', new \FabBackend\Model\eventCommandHandler());
                $entity = $this->commandBus->handle($this->domainEvent);
                die("saved");
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
