<?php

namespace Fab\Domain\Text\Object;
use \Fab\Domain\Text\Service\textValidate as textValidate;
use \LWddd\ValueObject as ValueObject;

class textData extends ValueObject
{
    public function __construct($values)
    {
        $allowedKeys = array(
                "id", 
                "key", 
                "content", 
                "language", 
                "category", 
                "first_date", 
                "last_date");
        
        parent::__construct($values, $allowedKeys, new textValidate());
    }
}