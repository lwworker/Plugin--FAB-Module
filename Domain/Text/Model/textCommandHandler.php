<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;

class textCommandHandler extends fabCommandHandler
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
    }
    
    public function addText(ValueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_text ( key, content, language, category, first_date, last_date ) VALUES ( :key, :content, :language, :category, :first_date, :last_date ) ");
        $this->db->bindParameter("key", "s", $entity->getValueByKey('key'));
        $this->db->bindParameter("content", "s", $entity->getValueByKey('content'));
        if(empty($entity->getValueByKey('language'))){
            $this->db->bindParameter("language", "s", "de");
        }else{
            $this->db->bindParameter("language", "s", $entity->getValueByKey('language'));
        }
        $this->db->bindParameter("category", "s", $entity->getValueByKey('category'));
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        $this->basePdbinsert("fab_text");
    }
    
    public function saveText($id, ValueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_text SET key = :key, content = :content, language = :language, category = :category, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("key", "s", $entity->getValueByKey('key'));
        $this->db->bindParameter("content", "s", $entity->getValueByKey('content'));
        if(empty($entity->getValueByKey('language'))){
            $this->db->bindParameter("language", "s", "de");
        }else{
            $this->db->bindParameter("language", "s", $entity->getValueByKey('language'));
        }
        $this->db->bindParameter("category", "s", $entity->getValueByKey('category'));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        $this->basePdbqueryWithEntityReturn($entity);
    }
    
    public function deleteText(Entity $entity)
    {
        $this->baseDelete($entity, "fab_text");
    }
    
    public function createTable()
    {
        $table_create_statement = "id int(11) NOT NULL AUTO_INCREMENT,
                                  key varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  content longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  language varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  category varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date int(14) NOT NULL,
                                  last_date int(14) NOT NULL,
                                  PRIMARY KEY (`id`) ";
        
        $this->baseCreateTable("fab_text", $table_create_statement);
        $this->updateTable();
    }
    
    public function updateTable()
    {
        return true;
        /*
         * Wenn es noch keine Erweiterung gibt, dann true zurückgeben
         * Für jede Erweiterung erst prüfen, ob die neue Spalte bereits vorhanden ist 
         * und wenn nicht, dann die Spalte mit "ALTER TABLE tablename ADD COLUMN ..." erstellen.
         * 
         */
    }
    
    public function setDebug($bool = true)
    {
        $this->baseSetDebug($bool);
    }
}