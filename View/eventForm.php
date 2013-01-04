<?php

namespace FabBackend\View;

class eventForm
{
    public function __construct(\lw_ddd_domainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->view = new \lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        if ($this->domainEvent->getEventName() == "showAddFormAction" || $this->domainEvent->getEventName() == "addEventAction") {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addEvent"));
            $this->view->type = "add";
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"saveEvent", "id" => $this->domainEvent->getId()));
            $this->view->type = "edit";
            if ($this->domainEvent->getEntity()->deleteAllowed()) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"deleteEvent","id"=>$this->domainEvent->getId()));
            }
        }
        if ($this->domainEvent->hasEntity() && !$this->view->errors) {
            $this->domainEvent->getEntity()->renderView($this->view);
        }
        else {
            $this->domainEvent->getPostValueObject()->renderView($this->view);
        }
        return $this->view->render();
    }
}