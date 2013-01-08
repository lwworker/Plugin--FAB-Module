<?php

namespace Fab\Domain\Text\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Text\Model\textCommandHandler as textCommandHandler;
use \Fab\Domain\Text\Model\textQueryHandler as textQueryHandler;
use \Fab\Domain\Text\Object\textData as textData;
use \Fab\Domain\Text\Service\textDecorator as textDecorator;

class text extends Entity
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
        $commandHandler = new textCommandHandler();
        return $commandHandler->deleteText($this);
    }

    public function save()
    {
        $commandHandler = new textCommandHandler();
        return $this->saveEntity($commandHandler);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $queryHandler = new textQueryHandler();
            $data = $queryHandler->getTextById($this->id);
            $this->setDataValueObject(new textData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Text cannot be loaded, because no ID is present');
        }
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = textDecorator::getInstance()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}