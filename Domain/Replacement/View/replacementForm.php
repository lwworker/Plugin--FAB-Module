<?php

namespace Fab\Domain\Replacement\View;
use \LWddd\DomainEvent as DomainEvent;
use \Fab\Library\fabView as fabView;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabDIC as DIC;

class replacementForm extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->dic = new DIC();
        $this->view = new lw_view(dirname(__FILE__).'/templates/replacementFormView.tpl.phtml');
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveReplacement", "id" => $this->domainEvent->getId()));
        $this->view->type = "edit";

        if ($this->domainEvent->hasEntity()) {
            $this->domainEvent->getEntity()->renderView($this->view);
        }
        
        $this->view->backUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showEventDetails", "id"=>$this->domainEvent->getId()));
        $config = $this->dic->getConfiguration();
        $this->view->mailDomain = $config['fab']['defaultMailDomain'];
        return $this->view->render();
    }
}