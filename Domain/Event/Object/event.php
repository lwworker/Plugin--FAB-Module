<?php

namespace Fab\Domain\Event\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Event\Model\eventCommandHandler as eventCommandHandler;
use \Fab\Domain\Event\Model\eventQueryHandler as eventQueryHandler;
use \Fab\Domain\Event\Object\eventData as eventData;
use \Fab\Domain\Event\Service\eventDecorator as eventDecorator;
use \lw_registry as lwRegistry;
use \Exception as Exception;

class event extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
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
            $commandHandler = new eventCommandHandler(lwRegistry::getInstance()->getEntry("db"));
            return $commandHandler->deleteEvent($this);
        }
        else {
            throw new Exception('Delete not allowed, because Event is active!');
        }
    }

    public function save()
    {
        $commandHandler = new eventCommandHandler(lwRegistry::getInstance()->getEntry("db"));
        return $this->saveEntity($commandHandler);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $queryHandler = new eventQueryHandler(lwRegistry::getInstance()->getEntry("db"));
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