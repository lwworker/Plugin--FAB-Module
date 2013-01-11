<?php

namespace Fab\Domain\Replacement\Service;
use \Fab\Domain\Replacement\Service\replacementDecorator as replacementDecorator;
use \LWddd\ValueObject as ValueObject;

class replacementDecorator
{
    public function __construct()
    {
    }
    
    public function setDefaultMailDomain($mailDomain)
    {
        $this->mailDomain = $mailDomain;
    }
    
    public function getInstance()
    {
        return new replacementDecorator();
    }
    
    public function decorate(ValueObject $valueObject)
    {
        $value = $valueObject->getValueByKey('stellvertreter_mail');
        if ($this->mailDomain) {
            $value = str_replace($this->mailDomain, "", $value);
        }
        return new ValueObject(array("stellvertreter_mail" => $value));
    }
}