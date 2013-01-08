<?php

namespace Fab\Library;
use \lw_response as lwResponse;

class fabResponse extends lwResponse
{
    
    private static $instance = null;
    
    public function __construct()
    {
    }
    
    public static function getInstance($useCollector = false)
    {
        if (self::$instance == null) {
            self::$instance = new fabResponse($useCollector);
        }
        return self::$instance;
    }    
    
    public function setReloadCmd($cmd, $parameters=false)
    {
        $this->reloadCmd = $cmd;
        $this->reloadParameters = $parameters;
    }
    
    public function setReloadUrl($url)
    {
        $this->reloadUrl = $url;
    }
    
    public function hasReloadUrl()
    {
        if (strlen(trim($this->reloadUrl))>0) {
            return true;
        }
        return false;
    }
    
    public function hasReloadCommand()
    {
        if (strlen(trim($this->reloadCmd))>0) {
            return true;
        }
        return false;
    }
    
    public function getReloadCommandWithParameters()
    {
        $this->reloadParameters['cmd'] = $this->reloadCmd;
        return $this->reloadParameters;
    }
}