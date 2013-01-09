<?php

namespace Fab\Domain\Event\View;
use \LWddd\EntityAggregate as EntityAggregate;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;

class eventListForResponsible extends fabView
{
    public function __construct(EntityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->view = new lw_view(dirname(__FILE__).'/templates/listForResponsibleView.tpl.phtml');
    }
    
    public function render()
    {
        $this->aggregate->renderView($this->view);
        return $this->view->render();
    }
}