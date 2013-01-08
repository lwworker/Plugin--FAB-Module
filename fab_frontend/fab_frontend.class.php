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
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);
        
        $response = \Fab\Library\fabResponse::getInstance();
        $cmd = $this->request->getAlnum('cmd');
        if ($this->request->getAlnum('cmd') == 'showEventListForResponsible' || !$this->request->getAlnum('cmd')) {
            $controller = new \Fab\Domain\Event\Controller\Controller($response);
            $controller->setSession(new \Fab\Library\fabSession());
            $cmd = 'showEventListForResponsible';
        }
        else {
            //$controller = new \Fab\Domain\Participant\Controller\Controller($response);
        }
        
        $response = $controller->execute($cmd, $this->request);
        
        if ($response->hasReloadCommand()) {
            $url = lw_page::getInstance()->getUrl($response->getReloadCommandWithParameters());
            $this->pageReload($url);
        }
        else {
            return $response->getOutputByName('FabOutput');
        }
    }
}
