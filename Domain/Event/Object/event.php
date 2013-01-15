<?php

namespace Fab\Domain\Event\Object;
use \LWddd\Entity as Entity;
use \Fab\Library\fabDIC as DIC;

class event extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
        $this->dic = new DIC();
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = $this->dic->getEventDecorator()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}