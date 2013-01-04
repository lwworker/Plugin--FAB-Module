<?php

namespace FabBackend\Object;

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
    
    public function save()
    {
        $commandHandler = new \FabBackend\Model\eventCommandHandler();
        return $this->saveEntity($commandHandler);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $queryHandler = new \FabBackend\Model\eventQueryHandler();
            $data = $queryHandler->getEventById($this->id);
            $this->setDataValueObject(new \FabBackend\Object\eventData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Event cannot be loaded, because no ID is present');
        }
    }
}
