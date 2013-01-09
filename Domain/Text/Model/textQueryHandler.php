<?php

namespace Fab\Domain\Text\Model;
use \lw_registry as lw_registry;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class textQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->setLanguage("de");
    }
    
    /**
     * Returns a list all saved texts for a specific category
     * @param string $category
     * @return array
     * @throws Exception
     */
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
    
    /**
     * Returns a list of saved and unique categories
     * @return array 
     */
    public function getAllUniqueCategories()
    {
        $this->baseGetAllUniqueValuesForAttribute("category", "fab_text");
    }
    
    /**
     * Returns a list of saved and unique languages
     * @return array 
     */
    public function getAllUniqueLanguages()
    {
        $this->baseGetAllUniqueValuesForAttribute("language", "fab_text");
    }
    
    /**
     * The param language will be checked if this language is already existing
     * @param string $lang
     * @return boolean
     */
    public function languageExists($lang)
    {
        $languages = $this->getAllUniqueLanguages();
        if(is_array($languages)){
            if(in_array($lang, $languages)){
                return true;
            }else{
                return false;
            }       
        }else{
            return false;
        }
    }
    
    /**
     * The param category will be checked if this category is already existing
     * @param string $lang
     * @return boolean
     */
    public function categoryExists($category)
    {
        $categories = $this->getAllUniqueCategories();
        if(is_array($categories)){
            if(in_array($category, $categories)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    /**
     * Returns all saved data for a specific text
     * @param int $id
     * @return array
     */
    public function getTextById($id)
    {
        $this->baseGetEntryById($id, "fab_text");
    }
    
    /**
     * 
     * @param string $lang
     */
    public function setLanguage($lang)
    {
        if ($this->languageExists($lang)) {
            $this->lang = $lang;
        }
    }
}