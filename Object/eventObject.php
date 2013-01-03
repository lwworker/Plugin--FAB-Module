<?php

class eventObject extends lw_ddd_entity
{
    public function __construct($data)
    {
        $this->allowedKeys = array("id", "name");
        parent::__construct($data);
    }

    public function setValueByKey($key, $value)
    {
        if (in_array($key, $this->allowedKeys)) {
            $this->data[$key] = $value;
        }
    }
    
    public function isDeleteable()
    {
        return true;
    }
    
}
