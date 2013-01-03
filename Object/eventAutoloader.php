<?php

namespace FabBackend\Object;

class eventAutoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) 
    {
        $className = str_replace('FabBackend\\', '', $className);
        $className = str_replace('\\', '/', $className);
        if (is_file(dirname(__FILE__).'/../'.$className.'.php')) {
            include_once(dirname(__FILE__).'/../'.$className.'.php');
        }
    }
}