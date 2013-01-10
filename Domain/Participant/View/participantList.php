<?php

namespace Fab\Domain\Participant\View;
use \LWddd\EntityAggregate as EntityAggregate;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;

class participantList extends fabView
{
    public function __construct(EntityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->view = new lw_view(dirname(__FILE__).'/templates/listView.tpl.phtml');
    }
    
    public function render()
    {
        $this->aggregate->renderView($this->view);
        $this->view->addUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showAddParticipantForm", "eventId"=>1));
        return $this->view->render();
    }
}