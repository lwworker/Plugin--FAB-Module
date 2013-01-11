<?php

namespace Fab\Domain\Replacement\Controller;
use \lw_response as lwResponse;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Replacement\View\replacementForm as replacementForm;
use \Fab\Domain\Replacement\Object\replacement as replacement;
use \Fab\Domain\Replacement\Object\replacementFactory as replacementFactory;

class Controller extends \LWddd\Controller
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "showListAction";
        $this->dic = new DIC();
    }
    
    public function saveReplacementAction()
    {
        $PostValueObjectFiltered = $this->dic->getReplacementFilter()->filter($this->domainEvent->getPostValueObject());

        $event = \Fab\Domain\Event\Object\eventFactory::buildEventByEventId($this->domainEvent->getId());
        
        $ReplacementValidationSevice = $this->dic->getReplacementValidationObject();
        $ReplacementValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $ReplacementValidationSevice->setEventEntity($event);

        $valid = $ReplacementValidationSevice->validate();
        if ($valid) {
            $replacementCommandHandler = $this->dic->getReplacementCommandHandler();
            $ok = $replacementCommandHandler->saveReplacementByEventId($this->domainEvent->getId(), $PostValueObjectFiltered->getValueByKey('stellvertreter_mail'));
            if ($ok > 0) {
                $this->response->setReloadCmd('showEventDetails', array("id"=>$this->domainEvent->getId()));
            }
            else {
                throw new \Exception('error saving the replacement');
            }
        }
        else {
            $this->showReplacementFormAction($ReplacementValidationSevice->getErrors());
        }        
    }    
    
    public function showReplacementFormAction($errors=false)
    {
        if (!$this->domainEvent->hasEntity()) {
            $this->domainEvent->setEntity(replacementFactory::buildReplacementByEventId($this->domainEvent->getId()));
        }
        $formView = new replacementForm($this->domainEvent);
        if ($errors) {
            $formView->setErrors($errors);
        }        
        $this->response->addOutputByName('FabOutput', $formView->render());
    }
}