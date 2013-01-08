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
        if (!$this->categoryExists($category)) {
            throw new Exception('...');
        }else{
            $this->db->setStatement("SELECT * FROM t:fab_text WHERE category = :category ");
            $this->db->bindParameter("category", "s", $category);
            return $this->db->pselect();
        }
    }
    
    public function getAllUniqueCategories()
    {
        $this->db->setStatement("SELECT DISTINCT category FROM t:fab_text ");
        return $this->db->pselect();
    }
    
    public function getAllUniqueLanguages()
    {
        $this->db->setStatement("SELECT DISTINCT language FROM t:fab_text ");
        return $this->db->pselect();
    }
    
    public function languageExists($lang)
    {
        $languages = $this->getAllUniqueLanguages();
        if(in_array($lang, $languages)){
            return true;
        }else{
            return false;
        }       
    }
    
    public function categoryExists($category)
    {
        $categories = $this->getAllUniqueCategories();
        if(in_array($category, $categories)){
            return true;
        }else{
            return false;
        }
    }
    
    public function getTextById($id)
    {
        $this->db->setStatement("SELECT * FROM t:fab_text WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
    
    public function setLanguage($lang)
    {
        if ($this->languageExists($lang)) {
            $this->lang = $lang;
        }
    }
}
