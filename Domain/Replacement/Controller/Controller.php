<?php

namespace Fab\Domain\Replacement\Controller;
use \lw_response as lwResponse;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Replacement\View\replacementForm as replacementFormView;
use \Fab\Domain\Replacement\Object\replacement as replacement;
use \Fab\Domain\Replacement\Model\replacementFactory as replacementFactory;
use \Fab\Domain\Replacement\Specification\isValid as isValid;

class Controller extends \LWddd\Controller
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "showListAction";
        $this->dic = new DIC();
    }
    
    public function showReplacementFormAction($errors=false)
    {
        if ($errors) {
            $entity = replacementFactory::getInstance()->buildNewReplacementFromValueObject($this->domainEvent->getDataValueObject());
        }
        else {
            $entity = $this->dic->getReplacementRepository()->getReplacementObjectById($this->domainEvent->getId());
        }
        $this->domainEvent->setEntity($entity);
        $formView = new replacementFormView($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabOutput', $formView->render());
    }    
    
    public function saveReplacementAction()
    {
        $DataValueObjectFiltered = $this->dic->getReplacementFilter()->filter($this->domainEvent->getDataValueObject());
        $entity = $this->dic->getReplacementRepository()->getReplacementObjectById($this->domainEvent->getId());
        $entity->setDataValueObject($DataValueObjectFiltered);
        $isValidSpecification = isValid::getInstance();
        $isValidSpecification->setEvent($this->dic->getEventRepository()->getEventObjectById($this->domainEvent->getId()));
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            try {
                $result = $this->dic->getReplacementRepository()->saveReplacementByEventId($this->domainEvent->getId(), $entity);
                if ($result > 0) {
                     $this->response->setReloadCmd('showEventDetails', array("id"=>$this->domainEvent->getId()));
                }
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        else {
            $this->showReplacementFormAction($isValidSpecification->getErrors());
        }
    }    
}