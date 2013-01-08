<?php

namespace Fab\Domain\Text\View;
use \LWddd\DomainText as DomainText;
use \lw_view as lw_view;
use \lw_page as lw_page;

class textForm
{
    public function __construct(DomainText $domainText)
    {
        $this->domainText = $domainText;
        $this->view = new lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
    }
    
    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        if ($this->domainText->getTextName() == "showAddFormAction" || $this->domainText->getTextName() == "addTextAction") {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"addText"));
            $this->view->type = "add";
        }
        else {
            $this->view->actionUrl = lw_page::getInstance()->getUrl(array("cmd"=>"saveText", "id" => $this->domainText->getId()));
            $this->view->type = "edit";
            if ($this->domainText->getEntity()->isDeleteable()) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = lw_page::getInstance()->getUrl(array("cmd"=>"deleteText","id"=>$this->domainText->getId()));
            }
        }
        if ($this->domainText->hasEntity() && !$this->view->errors) {
            $this->domainText->getEntity()->renderView($this->view);
        }
        else {
            $this->domainText->getPostValueObject()->renderView($this->view);
        }
        return $this->view->render();
    }
}