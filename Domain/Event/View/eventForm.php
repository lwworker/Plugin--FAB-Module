<?php

namespace Fab\Domain\Event\View;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;

class eventForm extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->view = new lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        if ($this->domainEvent->getEventName() == "showAddFormAction" || $this->domainEvent->getEventName() == "addEventAction") {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"addEvent"));
            $this->view->type = "add";
        }
        else {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveEvent", "id" => $this->domainEvent->getId()));
            $this->view->type = "edit";
            if ($this->domainEvent->getEntity()->isDeleteable()) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = lw_page::getInstance()->getUrl(array("cmd"=>"deleteEvent","id"=>$this->domainEvent->getId()));
            }
        }
        if ($this->domainEvent->hasEntity() && !$this->view->errors) {
            $this->domainEvent->getEntity()->renderView($this->view);
        }
        else {
            $this->domainEvent->getPostValueObject()->renderView($this->view);
        }
        $this->view->backurl = lw_page::getInstance()->getUrl(array("cmd"=>"showList"));
        return $this->view->render();
    }
}