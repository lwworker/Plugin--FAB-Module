<?php

namespace Fab\Domain\Country\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;
use \lw_db as lw_db;

class countryCommandHandler extends fabCommandHandler
{
    public function __construct(lw_db $db)
    {
        parent::__construct($db);
    }

    public function importCountries()
    {
        $data = array();
        $values = "";
        
        $file = fopen( dirname(__FILE__)."/data/countries.csv", 'r');
        while (($line = fgetcsv($file,100,";")) !== FALSE) {
            array_push($data, $line);
        }
        fclose($file);
        
        foreach ($data as $value){
            $values .= "('".$value[0]."', '".$value[1]."'),";
        }
        $values = substr($values, 0, strlen($values) - 1);
        
        $this->db->setStatement("INSERT INTO t:fab_laender ( land , bezeichnung ) VALUES ".$values." ");   
        $this->basePdbinsert("fab_laender");
    }
    
    public function createTable()
    {
        $table_create_statement = "land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  bezeichnung varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
        
        $this->baseCreateTable("fab_laender", $table_create_statement);
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