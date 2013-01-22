<?php

class fab_frontend extends lw_plugin
{

    public function __construct()
    {
        lw_plugin::__construct();
        $this->response->addHeaderItems('cssfile', $this->config['url']['media'].'bootstrap/css/bootstrap.css');
        $this->response->addHeaderItems('cssfile', $this->config['url']['resource'].'projects/abbrechnungsmodul/Assets/css/bootstrap-fileupload.min.css');
        $this->response->addHeaderItems('jsfile', $this->config['url']['media'].'jquery/jquery-1.9.0.min.js');
        $this->response->addHeaderItems('jsfile', $this->config['url']['media'].'bootstrap/js/bootstrap.min.js'); 
        $this->response->addHeaderItems('jsfile', $this->config['url']['resource'].'projects/abbrechnungsmodul/Assets/js/bootstrap-fileupload.min.js');
    }
    
    public function buildPageOutput()
    {
        if ($this->request->getInt("saved")==1) {
            $this->response->addHeaderItems('js', 'alert("saved"); location.href=location.href.replace(/&?saved=1/i, "");');
        }
        
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);
        
        $response = \Fab\Library\fabResponse::getInstance();
        $cmd = $this->request->getAlnum('cmd');
        if ($this->request->getAlnum('cmd') == 'showEventListForResponsible' || !$this->request->getAlnum('cmd')) {
            $controller = new \Fab\Domain\Event\Controller\Controller($response);
            $cmd = 'showEventListForResponsible';
        }
        elseif ($this->request->getAlnum('cmd') == 'showEventDetails') {
            $controller = new \Fab\Domain\Event\Controller\Controller($response);
        }
        elseif ($this->request->getAlnum('cmd') == 'showReplacementForm'
                || $this->request->getAlnum('cmd') == 'saveReplacement') {
            $controller = new \Fab\Domain\Replacement\Controller\Controller($response);
        }
        else {
            $controller = new \Fab\Domain\Participant\Controller\Controller($response);
        }
        $controller->setSession(new \Fab\Library\fabSession());
        
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
