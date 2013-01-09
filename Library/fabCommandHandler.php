<?php

namespace Fab\Library;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Exception as Exception;
use \lw_db as lw_db;

class fabCommandHandler 
{

    public function __construct(lw_db $db)
    {
        $this->db = $db;
    }
    
    public function handle($domainEvent)
    {
        $command = $domainEvent->getEventName();
        $this->$command($domainEvent->getEntity());
    }
    
    public function baseDelete(Entity $entity, $table_name)
    {
        if ($entity->isDeleteable() && $entity->getId() > 0) {
            $this->db->setStatement("DELETE FROM t:" . $table_name . " WHERE id = :id ");
            $this->db->bindParameter("id", "i", $entity->getId());
            
            $this->basePdbquery();
        }
        else { 
            throw new Exception('...'); 
        }
    }

    public function baseCreateTable($table_name, $table_create_statement)
    {
        if(!$this->db->tableExists($this->db->gt($table_name))){
            $this->db->setStatement("CREATE TABLE IF NOT EXISTS ".$this->db->gt($table_name)." ( ". $table_create_statement ." ); ");
        }
        $this->basePdbquery();
    }
    
    public function basePdbinsert($table_name)
    {
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $newId = $this->db->pdbinsert($this->db->gt($table_name));
            if ($newId > 0) {
                return $newId;
            }
            else {
                throw new Exception('...'); 
            }
        }
    }
    
    public function basePdbquery()
    {
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $ok = $this->db->pdbquery();
            if(!$ok){
                throw new Exception('...'); 
            }
            return $ok;
        }
    }
    
    public function baseSetDebug($bool)
    {
        if($bool === true){
            $this->debug = true;
        }else{
            $this->debug = false;
        }
    }
}