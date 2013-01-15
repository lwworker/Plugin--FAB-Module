<?php

namespace Fab\Domain\Participant\Controller;
use \Fab\Domain\Participant\Model\participantFactory as participantFactory;
use \Fab\Domain\Participant\View\participantList as participantListView;
use \Fab\Domain\Participant\View\participantForm as participantFormView;
use \Fab\Domain\Participant\View\participantCsvUploadForm as participantCsvUploadFormView;
use \Fab\Domain\Participant\View\participantCsvDownload as participantCsvDownloadView;
use \Fab\Domain\Participant\Service\prepareCsvUpload as prepareCsvUpload;
use \Fab\Domain\Participant\Specification\validationErrorsException as validationErrorsException;
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
    
    public function deleteParticipantAction()
    {
        try {
            $ok = $this->dic->getParticipantRepository()->deleteParticipantById($this->domainEvent->getId());
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }        
        $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
    }
    
    public function showParticipantListAction()
    {
        $aggregate = $this->dic->getParticipantRepository()->getParticipantsAggregateByEventId($this->domainEvent->getParameterByKey('eventId'));
        $listView = new participantListView($this->domainEvent, $aggregate);        
        $listView->setLwResponseObject($this->dic->getLwResponse());
        $listView->setLwConfiguration($this->dic->getConfiguration());
        $this->response->addOutputByName('FabOutput', $listView->render());
    }
    
    public function downloadCsvAction()
    {
        $aggregate = $this->dic->getParticipantRepository()->getParticipantsAggregateByEventId($this->domainEvent->getParameterByKey('eventId'));
        $csvView = new participantCsvDownloadView($this->domainEvent, $aggregate);        
        die($csvView->render());
    }
    
    public function showUploadCsvFormAction($errors = false)
    {
        $formView = new participantCsvUploadFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
    
    public function saveCsvAction()
    {
        $prepare = new prepareCsvUpload($this->domainEvent);
        $prepare->prepare();
        if ($prepare->isInvalid()) {
            $this->showUploadCsvFormAction($prepare->getInvalid());
            return;
        }
        $this->dic->getParticipantRepository()->saveCsvData($this->domainEvent->getParameterByKey('eventId'), $prepare->getAggregate());
        $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
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
        try {
            $result = $this->dic->getParticipantRepository()->saveParticipant($this->domainEvent->getParameterByKey('eventId'), false, $this->domainEvent->getDataValueObject());
            $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
        }
        catch (validationErrorsException $e) {
            $this->showAddParticipantFormAction($e->getErrors());
        }         
    }    
    
    public function saveParticipantAction()
    {
        try {
            $result = $this->dic->getParticipantRepository()->saveParticipant($this->domainEvent->getParameterByKey('eventId'), $this->domainEvent->getId(), $this->domainEvent->getDataValueObject());
            $this->response->setReloadCmd('showParticipantList', array('eventId'=>$this->domainEvent->getParameterByKey('eventId')));
        }
        catch (validationErrorsException $e) {
            $this->showAddParticipantFormAction($e->getErrors());
        }         
    }
}
