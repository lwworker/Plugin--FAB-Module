<?php

namespace Fab\Domain\Text\Controller;
use \Fab\Domain\Text\Object\textAggregateFactory as textAggregateFactory;
use \Fab\Domain\Text\Model\textQueryHandler as textQueryHandler;
use \Fab\Domain\Text\View\textList as textList;
use \Fab\Domain\Text\View\textForm as textForm;
use \Fab\Domain\Text\Object\text as text;
use \Fab\Domain\Text\Service\textFilter as textFilter;
use \Fab\Domain\Text\Service\textValidate as textValidate;
use \Fab\Domain\Text\Object\textData as textData;

class Controller extends \LWddd\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultAction = "showListAction";
    }
    
    public function showListAction()
    {
        $aggregate = textAggregateFactory::buildAggregateFromDomainText($this->domainText, new textQueryHandler());
        $listView = new textList($aggregate);        
        $this->response->addOutputByName('FabBackend', $listView->render());
    }
    
    public function showAddFormAction($errors=false)
    {
        $formView = new textForm($this->domainText);
        if ($errors) {
            $formView->setErrors($errors);
        }
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    public function showEditFormAction()
    {
        if (!$this->domainText->hasEntity()) {
            $this->setEntityById($this->domainText->getId());
        }
        $formView = new textForm($this->domainText);
        $this->response->addOutputByName('FabBackend', $formView->render());
    }
    
    protected function setEntityById($id)
    {
        $text = new text($id);
        $text->load();
        $this->domainText->setEntity($text);
    }
    
    public function saveTextAction()
    {
        $this->saveText($this->domainText->getId());
    }
    
    public function addTextAction()
    {
        $this->saveText();
    }
    
    public function deleteTextAction()
    {
        if (!$this->domainText->hasEntity()) {
            $this->setEntityById($this->domainText->getId());
        }
        $entity = $this->domainText->getEntity();
        if ($entity->isDeleteable()) {
            $entity->delete();
            die("deleted");
        }
    }
    
    protected function saveText($id=false)
    {
        $PostValueObjectFiltered = textFilter::getInstance()->filter($this->domainText->getPostValueObject());
        $TextValidationSevice = new textValidate();
        $TextValidationSevice->setValues($PostValueObjectFiltered->getValues());
        $valid = $TextValidationSevice->validate();
        if ($valid)
        {
            try {
                $TextDataValueObject = new textData($PostValueObjectFiltered->getValues());
            }
            catch (Exception $e) {
                die("error: ".$e->getMessage());
            }
            $entity = new text($id);
            $entity->setDataValueObject($TextDataValueObject);
            try {
                $result = $entity->save();
                if ($id > 0) {
                    die("saved");
                }
                else {
                    die("added");
                }
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else {
            $this->showAddFormAction($TextValidationSevice->getErrors());
        }
    }
}