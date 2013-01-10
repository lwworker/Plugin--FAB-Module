<?php

namespace Fab\Domain\Participant\Controller;
use \Fab\Domain\Participant\Object\participantAggregateFactory as participantAggregateFactory;
use \Fab\Domain\Participant\Object\participantData as participantData;
use \Fab\Domain\Participant\Object\participant as participant;
use \Fab\Domain\Participant\View\participantList as participantList;
use \Fab\Domain\Participant\View\participantForm as participantForm;
use \LWddd\Controller as dddController;
use \Fab\Library\fabDIC as DIC;
use \lw_response as lwResponse;

class Controller extends dddController
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "showListAction";
        $this->dic = new DIC();
    }
    
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }
    
    public function showParticipantListAction()
    {
        $aggregate = participantAggregateFactory::buildAggregateFromDomainEvent($this->domainEvent, $this->dic->getParticipantQueryHandler());
        $listView = new participantList($aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function showAddParticipantFormAction($errors = false)
    {
        $formView = new participantForm($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function addParticipantAction()
    {
        $ok = $this->saveParticipant($this->domainEvent->getId());
        if ($ok) {
            $this->response->setReloadCmd('showParticipantList', array('id'=>$this->eventId));
        }
    }    
    
    protected function saveParticipant($eventID, $id=false)
    {
        $PostValueObjectFiltered = $this->dic->getParticipantFilter()->filter($this->domainEvent->getPostValueObject());
        $ParticipantValidationSevice = $this->dic->getParticipantValidationObject(); 
        $ParticipantValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $valid = $ParticipantValidationSevice->validate();
        if ($valid)
        {
            try {
                $ParticipantDataValueObject = new participantData($PostValueObjectFiltered->getValues());
            }
            catch (Exception $e) {
                die("error: ".$e->getMessage());
            }
            
            $entity = new participant($id);
            $entity->setDataValueObject($ParticipantDataValueObject);
            
            try {
                $result = $entity->save();
                if ($result > 0) {
                    return true;
                }
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else {
            if ($id > 0) {
                $this->showEditParticipantFormAction($ParticipantValidationSevice->getErrors());
            }
            else {
                $this->showAddParticipantFormAction($ParticipantValidationSevice->getErrors());
            }
        }
    }    
    
}
