<?php

namespace Fab\Domain\Event\Object;

class event extends \lw_ddd_entity
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
        $commandHandler = new \Fab\Domain\Event\Model\eventCommandHandler();
        return $commandHandler->deleteEvent($this);
    }

    public function save()
    {
        $commandHandler = new \Fab\Domain\Event\Model\eventCommandHandler();
        return $this->saveEntity($commandHandler);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $queryHandler = new \Fab\Domain\Event\Model\eventQueryHandler();
            $data = $queryHandler->getEventById($this->id);
            $this->setDataValueObject(new \Fab\Domain\Event\Object\eventData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Event cannot be loaded, because no ID is present');
        }
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = \Fab\Domain\Event\Service\eventDecorator::getInstance()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}
