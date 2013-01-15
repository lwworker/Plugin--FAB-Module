<?php

namespace Fab\Domain\Event\View;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Event\Specification\isDeletable as isDeletable;

class eventForm extends fabView
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
        if ($this->domainEvent->getEventName() == "showAddFormAction" || $this->domainEvent->getEventName() == "addEventAction") {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"addEvent"));
            $this->view->type = "add";
        }
        else {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveEvent", "id" => $this->domainEvent->getId()));
            $this->view->type = "edit";
            
            if (isDeletable::getInstance()->isSatisfiedBy($this->domainEvent->getEntity())) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = lw_page::getInstance()->getUrl(array("cmd"=>"deleteEvent","id"=>$this->domainEvent->getId()));
            }
        }
        if ($this->domainEvent->hasEntity() && !$this->view->errors) {
            $this->domainEvent->getEntity()->renderView($this->view);
            $this->renderCountryOptions($this->domainEvent->getEntity()->getValueByKey('v_land'));
        }
        else {
            $this->domainEvent->getDataValueObject()->renderView($this->view);
            $this->renderCountryOptions($this->domainEvent->getDataValueObject()->getValueByKey('v_land'));
        }
        $this->view->backUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showList"));
        $config = $this->dic->getConfiguration();
        $this->view->mailDomain = $config['fab']['defaultMailDomain'];
        return $this->view->render();
    }
}