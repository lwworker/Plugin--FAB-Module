<?php

namespace Fab\Domain\Participant\View;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;
use \Fab\Library\fabDIC as DIC;

class participantForm extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->dic = new DIC();
        $this->view = new lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
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
    
    public function render()
    {
        if ($this->domainEvent->getEventName() == "showAddParticipantFormAction" || $this->domainEvent->getEventName() == "addParticipantAction") {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"addParticipant", "eventId"=>$this->domainEvent->getGetValueObject()->getValueByKey("eventId")));
            $this->view->type = "add";
        }
        else {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveParticipant", "id" => $this->domainEvent->getId()));
            $this->view->type = "edit";
            if ($this->domainEvent->getEntity()->isDeleteable()) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = lw_page::getInstance()->getUrl(array("cmd"=>"deleteParticipant","id"=>$this->domainEvent->getId()));
            }
        }
        if ($this->domainEvent->hasEntity() && !$this->view->errors) {
            $this->domainEvent->getEntity()->renderView($this->view);
            $this->renderCountryOptions($this->domainEvent->getEntity()->getValueByKey('v_land'));
        }
        else {
            $this->domainEvent->getPostValueObject()->renderView($this->view);
            $this->renderCountryOptions($this->domainEvent->getPostValueObject()->getValueByKey('v_land'));
        }
        $this->view->backurl = lw_page::getInstance()->getUrl(array("cmd"=>"showParticipantList", "id"=>$this->domainEvent->getGetValueObject()->getValueByKey("eventId")));
        $config = $this->dic->getConfiguration();
        return $this->view->render();
    }
}