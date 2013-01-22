<?php

class fab_backend extends lw_plugin
{

    public function __construct()
    {
        lw_plugin::__construct();		
        $this->response->addHeaderItems('cssfile', $this->config['url']['media'].'bootstrap/css/bootstrap.css');
        $this->response->addHeaderItems('jsfile', $this->config['url']['media'].'jquery/jquery-1.9.0.min.js');
        $this->response->addHeaderItems('jsfile', $this->config['url']['media'].'bootstrap/js/bootstrap.min.js'); 
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);
        
        $response = \Fab\Library\fabResponse::getInstance();
        $controller = new \Fab\Domain\Event\Controller\Controller($response);
        try {
            $response = $controller->execute($this->request->getAlnum('cmd'), $this->request);
        }
        catch (Exception $e) {
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
