<?php

namespace Fab\Domain\Event\View;

class eventList
{
    public function __construct(\lw_ddd_entityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->view = new \lw_view(dirname(__FILE__).'/templates/listView.tpl.phtml');
    }
    
    public function render()
    {
        $this->aggregate->renderView($this->view);
        $this->view->addUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"showAddForm"));
        return $this->view->render();
    }
}