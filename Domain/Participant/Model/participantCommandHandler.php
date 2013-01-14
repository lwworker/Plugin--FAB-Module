<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;

class participantCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table = "fab_teilnehmer";
    }
  
    /**
     * Creation of a new participant for a certain event
     * @param int $event_id
     * @param array $array
     * @return boolean
     */
    public function addEntity($eventId, $array)
    {
        $this->db->setStatement("INSERT INTO t:".$this->table." ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, unternehmenshortcut, strasse, plz, ort, land, mail, ust_id_nr, zahlweise, teilnehmer_intern, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :shortcutunternehmen, :strasse, :plz, :ort, :land, :mail, :ust_id_nr, :zahlweise, :teilnehmer_intern, :betrag, :first_date, :last_date ) ");
        $this->db->bindParameter("event_id", "i", $eventId);
        $this->db->bindParameter("anrede", "s", $array['anrede']);
        $this->db->bindParameter("sprache", "s", $array['sprache']);
        $this->db->bindParameter("titel", "s", $array['titel']);
        $this->db->bindParameter("nachname", "s", $array['nachname']);
        $this->db->bindParameter("vorname", "s", $array['vorname']);
        $this->db->bindParameter("institut", "s", $array['institut']);
        $this->db->bindParameter("unternehmen", "s", $array['unternehmen']);
        $this->db->bindParameter("shortcutunternehmen", "s", $array['unternehmenshortcut']);
        $this->db->bindParameter("strasse", "s", $array['strasse']);
        $this->db->bindParameter("plz", "s", $array['plz']);
        $this->db->bindParameter("ort", "s", $array['ort']);
        $this->db->bindParameter("land", "s", $array['land']);
        $this->db->bindParameter("mail", "s", $array['mail']);
        $this->db->bindParameter("ust_id_nr", "s", $array['ust_id_nr']);
        $this->db->bindParameter("zahlweise", "s", $array['zahlweise']);
        $this->db->bindParameter("referenznr", "s", $array['referenznr']);
        $this->db->bindParameter("teilnehmer_intern", "i", $array['teilnehmer_intern']);
        $this->db->bindParameter("betrag", "s", $array['betrag']);
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        return $this->basePdbinsert("fab_teilnehmer");
    }
    
    /**
     * Saving changes for a certain participant
     * @param int $id
     * @param array $array
     * @return boolean
     */
    public function saveEntity($id, $array)
    {
        $this->db->setStatement("UPDATE t:".$this->table." SET anrede = :anrede, sprache = :sprache, titel = :titel, nachname = :nachname, vorname = :vorname, institut = :institut, unternehmen = :unternehmen, unternehmenshortcut = :shortcutunternehmen,  strasse = :strasse, plz = :plz, ort = :ort, land = :land, mail = :mail, ust_id_nr = :ust_id_nr, zahlweise = :zahlweise, teilnehmer_intern = :teilnehmer_intern, betrag = :betrag, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("anrede", "s", $array['anrede']);
        $this->db->bindParameter("sprache", "s", $array['sprache']);
        $this->db->bindParameter("titel", "s", $array['titel']);
        $this->db->bindParameter("nachname", "s", $array['nachname']);
        $this->db->bindParameter("vorname", "s", $array['vorname']);
        $this->db->bindParameter("institut", "s", $array['institut']);
        $this->db->bindParameter("unternehmen", "s", $array['unternehmen']);
        $this->db->bindParameter("shortcutunternehmen", "s", $array['unternehmenshortcut']);
        $this->db->bindParameter("strasse", "s", $array['strasse']);
        $this->db->bindParameter("plz", "s", $array['plz']);
        $this->db->bindParameter("ort", "s", $array['ort']);
        $this->db->bindParameter("land", "s", $array['land']);
        $this->db->bindParameter("mail", "s", $array['mail']);
        $this->db->bindParameter("ust_id_nr", "s", $array['ust_id_nr']);
        $this->db->bindParameter("zahlweise", "s", $array['zahlweise']);
        $this->db->bindParameter("teilnehmer_intern", "i", $array['teilnehmer_intern']);
        $this->db->bindParameter("betrag", "s", $array['betrag']);
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        return $this->basePdbquery();
    }
    
    /**
     * The existance of fab_teilnehmer will be checked and created if the table is missing
     * @return boolean
     */
    public function createTable()
    {
        $table_create_statement = "id int(11) NOT NULL AUTO_INCREMENT,
                                  event_id int(11) NOT NULL,
                                  anrede varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  sprache varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  titel varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  nachname varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  vorname varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  institut varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  unternehmen varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  strasse varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  plz varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ort varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  mail varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  veranstaltung varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ust_id_nr varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  zahlweise varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  referenznr varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  teilnehmer_intern int(1) NOT NULL,
                                  auftragsnr varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  betrag varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date bigint(14) NOT NULL,
                                  last_date bigint(14) NOT NULL,
                                  PRIMARY KEY (id) ";
        
        return $this->baseCreateTable($this->table, $table_create_statement);
        $this->updateTable();    
    }
    
    /**
     * Execute changes for the table fab_teilnehmer
     * @return boolean
     */
    public function updateTable()
    {
        $sql = "ALTER TABLE t:".$this->table." ADD unternehmenshortcut VARCHAR( 10 ) NOT NULL AFTER unternehmen ";
        return true;
        /*
         * Wenn es noch keine Erweiterung gibt, dann true zurueckgeben
         * Fuer jede Erweiterung erst pruefen, ob die neue Spalte bereits vorhanden ist 
         * und wenn nicht, dann die Spalte mit "ALTER TABLE tablename ADD COLUMN ..." erstellen.
         * 
         */
    }
}