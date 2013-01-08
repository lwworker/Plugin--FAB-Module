<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;

class textQueryHandler
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
        $this->setLanguage("de");
    }
    
    public function getAllTextsByCategory($category)
    {
        if (!$this->categoryExists($lang)) {
            throw new Exception('...');
        }
    }
    
    public function getAllUniqueCategories()
    {
        
    }
    
    public function getAllUniqueLanguages()
    {
        
    }
    
    public function languageExists($lang)
    {
        
    }
    
    public function categoryExists($lang)
    {
        
    }
    
    public function setLanguage($lang)
    {
        if ($this->languageExists($lang)) {
            $this->lang = $lang;
        }
    }
}