<?php

namespace Fab\Domain\Event\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Exception as Exception;
use \Fab\Library\fabDIC as DIC;

class event extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
        $this->dic = new DIC();
    }

    public function isDeleteable_del()
    {
        if ($this->getValueByKey('anmeldefrist_beginn') < date("Ymd") && $this->getValueByKey('anmeldefrist_ende') > date("Ymd")) {
            return false;
        }
        return true;
    }
    
    public function delete_del()
    {
        if ($this->isDeleteable()) {
            return $this->dic->getEventCommandHandler()->deleteEvent($this);
        }
        else {
            throw new Exception('Delete not allowed, because Event is active!');
        }
    }

    public function save_del()
    {
        if ($this->id > 0 ) {
            $result = $this->dic->getEventCommandHandler()->saveEntity($this->id, $this->valueObject);
        }
        else {
            $result = $this->dic->getEventCommandHandler()->addEntity($this->valueObject);
            $this->id = $result;
        }
        return $this->finishSaveResult($result);
    }
    
    public function load_del()
    {
        echo "<pre>";
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        exit();
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = $this->dic->getEventDecorator()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}