<?php

namespace Fab\Domain\Country\View;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \Fab\Domain\Country\Model\countryQueryHandler as countryQueryHandler;
class countryOptions
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->queryHandler = new countryQueryHandler();
        $this->view = new lw_view(dirname(__FILE__).'/templates/selectView.tpl.phtml');
    }
    
    public function setSelectedCountryByshortcut($shortcut)
    {
        $this->selected = $shortcut;
    }
    
    public function render()
    {
        $this->view->selected = $this->selected;
        return $this->view->render();
    }
}