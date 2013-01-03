<?php

class fab_backend extends lw_plugin
{

    public function __construct()
    {
        \lw_plugin::__construct();
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/Object/eventAutoloader.php');
        $autoloader = new FabBackend\Object\eventAutoloader();
        $controller = new FabBackend\Controller\BackendController();
        $response = $controller->execute($this->request);
        return $response->getOutputByName('FabBackend');
    }

}
