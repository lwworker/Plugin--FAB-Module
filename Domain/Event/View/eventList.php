<?php

namespace Fab\Domain\Event\View;
use \LWddd\EntityAggregate as EntityAggregate;
use \lw_view as lw_view;
use \lw_page as lw_page;

class eventList
{
    public function __construct(EntityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->view = new lw_view(dirname(__FILE__).'/templates/listView.tpl.phtml');
    }
    
    public function render()
    {
        $this->aggregate->renderView($this->view);
        $this->view->addUrl = lw_page::getInstance()->getUrl(array("cmd"=>"showAddForm"));
        return $this->view->render();
    }
}