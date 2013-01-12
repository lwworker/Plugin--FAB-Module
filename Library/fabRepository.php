<?php

namespace Fab\Library;
use \Fab\Library\fabDIC as DIC;

class fabRepository 
{
    public function __construct()
    {
        $this->dic = new DIC();
    }
}