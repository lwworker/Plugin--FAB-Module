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
    
    /**
     * 
     * @param \LWddd\Entity $entity
     * @return true/exception
     */
    public function deleteEntityById($id)
    {
        try {
            $this->db->setStatement("DELETE FROM t:".$this->table." WHERE id = :id ");
            $this->db->bindParameter("id", "i", $id);
            return $this->basePdbquery();
        }
        catch (Exception $e) { 
            throw new Exception($e->getMessage()); 
        }
    }

    /**
     * Creation of a table
     * @param string $table_name
     * @param string $table_create_statement
     * @return boolean
     */
    public function baseCreateTable($table_name, $table_create_statement)
    {
        if(!$this->db->tableExists($this->db->gt($table_name))){
            $this->db->setStatement("CREATE TABLE IF NOT EXISTS ".$this->db->gt($table_name)." ( ". $table_create_statement ." ); ");
            return $this->basePdbquery();
        }
        return true;
    }
    
    /**
     * Returns a prepared sql-statement if debug switch is on or the result
     * of the entry insertion.
     * @param string $table_name
     * @return boolean
     * @throws Exception
     */
    public function basePdbinsert($table_name)
    {
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $newId = $this->db->pdbinsert($this->db->gt($table_name));
            if ($newId > 0) {
                #return $newId;
                return true;
            }
            else {
                throw new Exception('...'); 
            }
        }
    }
    
    /**
     * Returns a prepared sql-statement if debug switch is on or the result
     * of a database query.
     * @return boolean
     * @throws Exception
     */
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
    
    /**
     * Sets the debug switch on/off
     * @param type $bool
     * @return bool
     */
    public function setDebug($bool)
    {
        if($bool === true){
            $this->debug = true;
        }else{
            $this->debug = false;
        }
    }
}
