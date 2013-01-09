<?php

namespace Fab\Domain\Event\View;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;
use \Fab\Domain\Country\View\countryOptions as countryOptions;

class eventDetails extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->view = new lw_view(dirname(__FILE__).'/templates/detailView.tpl.phtml');
    }
    
    public function render()
    {
        if ($this->domainEvent->hasEntity()) {
            $this->domainEvent->getEntity()->renderView($this->view);
        }
        $this->view->backurl = lw_page::getInstance()->getUrl(array("cmd"=>"showEventListForResponsible"));
        $this->view->editReplacementUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showReplacementForm", "id" =>  $this->domainEvent->getId()));
        return $this->view->render();
    }
}