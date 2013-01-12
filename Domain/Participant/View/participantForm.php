<?php

namespace Fab\Domain\Participant\View;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \LWddd\DomainEvent as DomainEvent;
use \Fab\Library\fabView as fabView;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Participant\Specification\isDeletable as isDeletable;

class participantForm extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->dic = new DIC();
        $this->view = new lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
        if ($domainEvent->hasEntity()) {
            $this->entity = $domainEvent->getEntity();
        }
        $this->entityId = $domainEvent->getId();
        $this->eventId = $domainEvent->getParameterByKey("eventId");
        $this->domainCommand = $domainEvent->getEventName();
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function renderCountryOptions($countryShortcut)
    {
        $countryOptions = $this->dic->getCountryOptions();
        $countryOptions->setSelectedCountryByshortcut($countryShortcut);
        $this->view->countryOptions = $countryOptions->render();
    }
    
    protected function setAddParameter()
    {
        $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"addParticipant", "eventId"=>$this->eventId));
        $this->view->type = "add";
    }
    
    protected function setEditParameter()
    {
        $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveParticipant", "id" => $this->entityId, "eventId"=>$this->eventId));
        $this->view->type = "edit";
        $this->setDeleteParameter();
    }
    
    protected function setDeleteParameter()
    {
        if (isDeletable::getInstance()->isSatisfiedBy($this->entity)) {
            $this->view->deleteAllowed = true;
            $this->view->deleteUrl = lw_page::getInstance()->getUrl(array("cmd"=>"deleteParticipant","id"=>$this->entityId, "eventId"=>$this->eventId));
        }
    }
    
    public function render()
    {
        if ($this->domainCommand == "showAddParticipantFormAction" || $this->domainCommand == "addParticipantAction") {
            $this->setAddParameter();
        }
        else {
            $this->setEditParameter();
        }
        $this->entity->renderView($this->view);
        $this->renderCountryOptions($this->entity->getValueByKey('v_land'));
        $this->view->backurl = lw_page::getInstance()->getUrl(array("cmd"=>"showParticipantList", "id"=>$this->eventId));
        return $this->view->render();
    }
}