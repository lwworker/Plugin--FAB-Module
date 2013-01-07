<?php

namespace Fab\Service\Autoloader;

class fabAutoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) 
    {
        if (strstr($className, 'LWddd')) {
            $config = \lw_registry::getInstance()->getEntry('config');
            $path = $config['plugin_path']['lw'].'lw_ddd';
            $filename = str_replace('LWddd', $path, $className);
        }
        else {
            $path = dirname(__FILE__).'/../..';
            $filename = str_replace('Fab', $path, $className);
        }
        $filename = str_replace('\\', '/', $filename).'.php';
        
        if (is_file($filename)) {
            include_once($filename);
        }
    }
}