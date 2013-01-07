<?php

class fab_frontend extends lw_plugin
{

    public function __construct()
    {
        lw_plugin::__construct();
        $this->response->addHeaderItems('cssfile', $this->config['url']['client'].'assets/css/bootstrap.min.css');
        $this->response->addHeaderItems('jsfile', $this->config['url']['client'].'assets/js/jquery-1.8.3.min.js');
        $this->response->addHeaderItems('jsfile', $this->config['url']['client'].'assets/js/bootstrap.min.js');
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/Object/eventAutoloader.php');
        $autoloader = new FabBackend\Object\eventAutoloader();
        $controller = new FabBackend\Controller\BackendController();
        $response = $controller->execute($this->request);
        return $response->getOutputByName('FabFrontend');
    }
}
