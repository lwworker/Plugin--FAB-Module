<?php

namespace Fab\Library;
use \lw_db as lw_db;

class fabQueryHandler 
{
    public function __construct(lw_db $db)
    {
        $this->db = $db;
    }
    
    /**
     * Returns an entry with specific id
     * @param int $id
     * @param string $table_name
     * @return array
     */
    public function baseGetEntryById($id, $table_name)
    {
        $this->db->setStatement("SELECT * FROM t:" . $table_name . " WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
    
    /**
     * Returns an array of all unique values for a certain attribute
     * @param string $attribute
     * @param string $table_name
     * @return array
     */
    public function baseGetAllUniqueValuesForAttribute($attribute, $table_name)
    {
        $this->db->setStatement("SELECT DISTINCT " . $attribute . " FROM t:" . $table_name . " ");
        return $this->db->pselect();
    }
    
    /**
     * Returns a sorted array of entries with a certain attribute-value
     * @param string $table_name
     * @param string $attribute
     * @param string $attribute_type
     * @param string $attribute_value
     * @param string $order_attribute
     * @param string $order_type
     * @return array
     */
    public function baseLoadEntriesByAttributeWithOrder($table_name, $attribute, $attribute_type, $attribute_value, $order_attribute, $order_type)
    {
        $this->db->setStatement("SELECT * FROM t:" . $table_name . " WHERE " . $attribute . " = :attribute_value ORDER BY " . $order_attribute . " " . $order_type . " ");
        $this->db->bindParameter("attribute_value", $attribute_type, $attribute_value);
        return $this->db->pselect();
    }
}