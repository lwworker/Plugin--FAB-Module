<?php

namespace Fab\Domain\Participant\View;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \LWddd\DomainEvent as DomainEvent;
use \Fab\Library\fabView as fabView;
use \Fab\Library\fabDIC as DIC;

class participantCsvUploadForm extends fabView
{
    public function __construct(DomainEvent $domainEvent)
    {
        $this->dic = new DIC();
        $this->view = new lw_view(dirname(__FILE__).'/templates/csvUploadView.tpl.phtml');
        $this->eventId = $domainEvent->getParameterByKey("eventId");
        $this->domainCommand = $domainEvent->getEventName();
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }

    public function render()
    {
        $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveCsv", "eventId"=>$this->eventId));        
        $this->view->backurl = lw_page::getInstance()->getUrl(array("cmd"=>"showParticipantList", "eventId"=>$this->eventId));
        return $this->view->render();
    }
}