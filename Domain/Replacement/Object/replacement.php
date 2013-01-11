<?php

namespace Fab\Domain\Replacement\Object;
use \LWddd\Entity as Entity;
use \Fab\Domain\Replacement\Object\replacementData as replacementData;
use \Exception as Exception;
use \Fab\Library\fabDIC as DIC;

class replacement extends Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
        $this->dic = new DIC();
    }

    public function save()
    {
        $result = $this->dic->getReplacementCommandHandler()->saveEntity($this->id, $this->valueObject);
        return $this->finishSaveResult($result);
    }
    
    public function load()
    {
        if ($this->id > 0) {
            $data = $this->dic->getReplacementQueryHandler()->getReplacementByEventId($this->id);
            $this->setDataValueObject(new replacementData($data));
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            throw new Exception('Replacement cannot be loaded, because no EventId is present');
        }
    }
    
    public function renderView($view)
    {
        $ValueObjectDecorated = $this->dic->getReplacementDecorator()->decorate($this->valueObject);
        $view->entity = $ValueObjectDecorated->getValues();
    }    
}