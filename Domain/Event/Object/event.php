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

    public function isDeleteable()
    {
        $this->load();
        if ($this->getValueByKey('anmeldefrist_beginn') < date("Ymd") && $this->getValueByKey('anmeldefrist_ende') > date("Ymd")) {
            return false;
        }
        return true;
    }
    
    public function delete()
    {
        if ($this->isDeleteable()) {
            return $this->dic->getEventCommandHandler()->deleteEvent($this);
        }
        else {
            throw new Exception('Delete not allowed, because Event is active!');
        }
    }

    public function save()
    {
        return $this->saveEntity($this->dic->getEventCommandHandler());
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $data = $this->dic->getEventQueryHandler()->getEventById($this->id);
            $this->setDataValueObject(new eventData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Event cannot be loaded, because no ID is present');
        }
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = $this->dic->getEventDecorator()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}