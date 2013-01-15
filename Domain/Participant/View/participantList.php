<?php

namespace Fab\Domain\Participant\View;
use \LWddd\EntityAggregate as EntityAggregate;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;

class participantList extends fabView
{
    public function __construct(DomainEvent $domainEvent, EntityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->view = new lw_view(dirname(__FILE__).'/templates/listView.tpl.phtml');
        $this->domainEvent = $domainEvent;
    }
    
    public function setLwResponseObject($response)
    {
        $this->lwResponse = $response;
    }
    
    public function setLwConfiguration($config)
    {
        $this->lwConfig = $config;
    }
    
    public function render()
    {
        $this->lwResponse->addHeaderItems('cssfile', $this->lwConfig["url"]["media"].'jquery/datatables/media/css/demo_page.css');
        $this->lwResponse->addHeaderItems('cssfile', $this->lwConfig["url"]["media"].'jquery/datatables/media/css/header.css');
        $this->lwResponse->addHeaderItems('cssfile', $this->lwConfig["url"]["media"].'jquery/datatables/media/css/demo_table_jui.css');
        $this->lwResponse->addHeaderItems('jsfile', $this->lwConfig["url"]["media"].'jquery/datatables/media/js/jquery.dataTables.min.js');
        $this->lwResponse->usejQueryUI();
        
        $this->aggregate->renderView($this->view);
        $this->view->addUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showAddParticipantForm", "eventId"=>$this->domainEvent->getParameterByKey('eventId')));
        $this->view->backUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showEventListForResponsible"));
        $this->view->uploadUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showUploadCsvForm", "eventId" => $this->domainEvent->getParameterByKey('eventId')));
        $this->view->downloadUrl = lw_page::getInstance()->getUrl(array("cmd"=>"downloadCsv", "eventId" => $this->domainEvent->getParameterByKey('eventId')));
        $this->view->editReplacementUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showReplacementForm", "id" => $this->domainEvent->getParameterByKey('eventId')));
        $this->view->detailsUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showEventDetails", "id" => $this->domainEvent->getParameterByKey('eventId')));
        $this->view->response = $this->domainEvent->getParameterByKey("response");
        return $this->view->render();
    }
}