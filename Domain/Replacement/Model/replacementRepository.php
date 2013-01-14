<?php

namespace Fab\Domain\Replacement\Model;
use \Fab\Library\fabRepository as fabRepository;
use \Fab\Domain\Replacement\Object\replacement as replacement;
use \LWddd\ValueObject as ValueObject;

class replacementRepository extends fabRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $this->commandHandler = new replacementCommandHandler($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $this->queryHandler = new replacementQueryHandler($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
    
    public function buildReplacementObjectByArray($data)
    {
        $event = new replacement($data['id']);
        $event->setDataValueObject(new ValueObject($data));
        $event->setLoaded();
        $event->unsetDirty();
        return $event;
    }
    
    public function getReplacementObjectById($id)
    {
        $data = $this->getQueryHandler()->loadReplacementByEventId($id);
        return $this->buildReplacementObjectByArray($data);
    }
    
    public function saveReplacementByEventId($eventId, replacement $replacement)
    {
        $result = $this->getCommandHandler()->saveReplacementByEventId($eventId, $replacement->getValueByKey('stellvertreter_mail'));
        if ($result) {
            $replacement->setLoaded();
            $replacement->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $replacement->setLoaded();
            }
            else {
                $replacement->unsetLoaded();
            }
            $replacement->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }
        return $eventId;
    }
}