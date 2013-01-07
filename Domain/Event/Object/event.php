<?php

namespace Fab\Domain\Event\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Event\Model\eventCommandHandler as eventCommandHandler;
use \Fab\Domain\Event\Model\eventQueryHandler as eventQueryHandler;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Fab\Domain\Event\Service\eventDecorator as eventDecorator;

class event extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
    }

    public function isDeleteable()
    {
        return true;
    }
    
    public function delete()
    {
        $commandHandler = new eventCommandHandler();
        return $commandHandler->deleteEvent($this);
    }

    public function save()
    {
        $commandHandler = new eventCommandHandler();
        return $this->saveEntity($commandHandler);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $queryHandler = new eventQueryHandler();
            $data = $queryHandler->getEventById($this->id);
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
        $ValueObjectDecorated = eventDecorator::getInstance()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}