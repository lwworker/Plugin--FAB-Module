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
            $cmd = 'showEventListForResponsible';
        }
        elseif ($this->request->getAlnum('cmd') == 'showEventDetails' 
                || $this->request->getAlnum('cmd') == 'showReplacementForm'
                || $this->request->getAlnum('cmd') == 'saveReplacement') {
            $controller = new \Fab\Domain\Event\Controller\Controller($response);
        }
        else {
            $controller = new \Fab\Domain\Participant\Controller\Controller($response);
            $controller->setEventId($this->request->getInt('eventId')); 
        }
        $controller->setSession(new \Fab\Library\fabSession());
        
        try {
            $response = $controller->execute($cmd, $this->request);
        }
        catch(Exception $e) {
            die($e->getMessage());
        }            
        
        if ($response->hasReloadCommand()) {
            $url = lw_page::getInstance()->getUrl($response->getReloadCommandWithParameters());
            $this->pageReload($url);
        }
        else {
            return $response->getOutputByName('FabOutput');
        }
    }
}
