<?php

class eventCommandHandlerModel
{
    public function __construct()
    {
        $this->db = lw_registry::getInstance()->getEntry('db');
    }
    
    public function handle($domainEvent)
    {
        $command = $domainEvent->getEventName();
        $this->$command($domainEvent->getEntity());
    }
    
    public function addEventAction(lw_ddd_entity $entity)
    {
        if ($entity->isValid()) {
            $this->db->setStatement("INSERT INTO t:tablename ( name ) VALUES ( :name )");
            $this->db->bindParameter("name", "s", $entity->getValueByKey('name'));
            $newId = $this->db->pdbinsert($this->db->gt('tablename'));
            if ($newId > 0) {
                $entity->setId($newId);
                return $entity;
            }
            else {
                throw new Exception('...'); 
            }
        } 
        else { 
            throw new Exception('...'); 
        }
    }
    
    public function editEventAction(lw_ddd_entity $entity)
    {
        if ($entity->isValid() && $entity->getId() > 0) {
            $this->db->setStatement("UPDATE t:tablename SET name = :name WHERE id = :id ");
            $this->db->bindParameter("name", "s", $entity->getValueByKey('name'));
            $this->db->bindParameter("id", "i", $entity->getId());
            $ok = $this->db->pdquery();
            if ($ok) {
                return $entity;
            }
            else {
                throw new Exception('...'); 
            }
        } 
        else { 
            throw new Exception('...'); 
        }
    }
    
    public function deleteEventAction(lw_ddd_entity $entity)
    {
        if ($entity->isDeleteable() && $entity->getId() > 0) {
            $this->db->setStatement("DELETE FROM t:tablename WHERE id = :id ");
            $this->db->bindParameter("id", "i", $entity->getId());
            $ok = $this->db->pdquery();
            if ($ok) {
                return $entity;
            }
            else {
                throw new Exception('...'); 
            }
        }
        else { 
            throw new Exception('...'); 
        }
    }
}
