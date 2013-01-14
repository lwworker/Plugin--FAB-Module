<?php

namespace Fab\Domain\Participant\Controller;
use \Fab\Domain\Participant\Object\participantAggregateFactory as participantAggregateFactory;
use \Fab\Domain\Participant\Model\participantFactory as participantFactory;
use \Fab\Domain\Participant\Object\participantData as participantData;
use \Fab\Domain\Participant\Object\participant as participant;
use \Fab\Domain\Participant\View\participantList as participantListView;
use \Fab\Domain\Participant\View\participantForm as participantFormView;
use \Fab\Domain\Participant\View\participantCsvDownload as participantCsvDownloadView;
use \Fab\Domain\Participant\Specification\isValid as isValid;
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
    
    public function showParticipantListAction()
    {
        $aggregate = $this->dic->getParticipantRepository()->getParticipantsAggregateByEventId($this->domainEvent->getParameterByKey('eventId'));
        $listView = new participantListView($this->domainEvent, $aggregate);        
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function downloadCsvAction()
    {
        $aggregate = $this->dic->getParticipantRepository()->getParticipantsAggregateByEventId($this->domainEvent->getParameterByKey('eventId'));
        $csvView = new participantCsvDownloadView($this->domainEvent, $aggregate);        
        die($csvView->render());
    }
    
    public function showAddParticipantFormAction($errors = false)
    {
        $entity = participantFactory::getInstance()->buildNewParticipantFromValueObject($this->domainEvent->getDataValueObject());

        $this->domainEvent->setEntity($entity);
        $formView = new participantFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function showEditParticipantFormAction($errors = false)
    {
        if ($errors) {
            $entity = participantFactory::getInstance()->buildNewParticipantFromValueObject($this->domainEvent->getDataValueObject());
        }
        else {
            $entity = $this->dic->getParticipantRepository()->getParticipantObjectById($this->domainEvent->getId());
        }
        $this->domainEvent->setEntity($entity);
        $formView = new participantFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function addParticipantAction()
    {
        $ok = $this->saveParticipant();
        if ($ok) {
            $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
        }
    }    
    
    public function saveParticipantAction()
    {
        $ok = $this->saveParticipant($this->domainEvent->getId());
        if ($ok) {
            $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
        }
    }
    
    protected function saveParticipant($id=false)
    {
        $DataValueObjectFiltered = $this->dic->getParticipantFilter()->filter($this->domainEvent->getDataValueObject());
        if (!$id) {
            $entity = participantFactory::getInstance()->buildNewParticipantFromValueObject($DataValueObjectFiltered);
        }
        else {
            $entity = $this->dic->getParticipantRepository()->getParticipantObjectById($id);
            $entity->setDataValueObject($DataValueObjectFiltered);
        }
        $isValidSpecification = isValid::getInstance();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            try {
                $result = $this->dic->getParticipantRepository()->saveParticipant($this->domainEvent->getParameterByKey('eventId'), $entity);
                if ($result > 0) {
                    return true;
                }
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        else {
            if ($id > 0) {
                $this->showEditParticipantFormAction($isValidSpecification->getErrors());
            }
            else {
                $this->showAddParticipantFormAction($isValidSpecification->getErrors());
            }
        }
    }
}
