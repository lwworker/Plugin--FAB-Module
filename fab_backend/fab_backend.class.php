<?php

class fab_backend extends lw_plugin
{

    public function __construct()
    {
        lw_plugin::__construct();
        $this->response->addHeaderItems('cssfile', $this->config['url']['client'].'assets/css/bootstrap.css');
        $this->response->addHeaderItems('jsfile', $this->config['url']['client'].'assets/js/jquery-1.8.3.min.js');
        $this->response->addHeaderItems('jsfile', $this->config['url']['client'].'assets/js/bootstrap.min.js');
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);
        $controller = new Fab\Domain\Event\Controller\Controller();
        $response = $controller->execute($this->request);
        return $response->getOutputByName('FabBackend');
    }
}