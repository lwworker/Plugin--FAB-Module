<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;

class eventCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table = "fab_tagungen";
    }
        
    /**
     * Creation of a new Event
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function addEntity($array)
    {
        $this->db->setStatement("INSERT INTO t:".$this->table." ( buchungskreis, v_schluessel, auftragsnr, bezeichnung, v_land, v_ort, anmeldefrist_beginn, anmeldefrist_ende, v_beginn, v_ende, cpd_konto, erloeskonto, steuerkennzeichen, steuersatz, ansprechpartner, ansprechpartner_tel, organisationseinheit, ansprechpartner_mail, standardbetrag, first_date, last_date ) VALUES ( :buchungskreis, :v_schluessel, :auftragsnr, :bezeichnung, :v_land, :v_ort, :anmeldefrist_beginn, :anmeldefrist_ende, :v_beginn, :v_ende, :cpd_konto, :erloeskonto, :steuerkennzeichen, :steuersatz, :ansprechpartner, :tel_ansprechpartner, :organisationseinheit, :mail_ansprechpartner, :standardbetrag, :first_date, :last_date ) ");
        $this->db->bindParameter("buchungskreis", "s", $array['buchungskreis']);
        $this->db->bindParameter("v_schluessel", "s", $array['v_schluessel']);
        $this->db->bindParameter("auftragsnr", "s", $array['auftragsnr']);
        $this->db->bindParameter("bezeichnung", "s", $array['bezeichnung']);
        $this->db->bindParameter("v_land", "s", $array['v_land']);
        $this->db->bindParameter("v_ort", "s", $array['v_ort']);
        $this->db->bindParameter("anmeldefrist_beginn", "i", $array['anmeldefrist_beginn']);
        $this->db->bindParameter("anmeldefrist_ende", "i", $array['anmeldefrist_ende']);
        $this->db->bindParameter("v_beginn", "i", $array['v_beginn']);
        $this->db->bindParameter("v_ende", "i", $array['v_ende']);
        $this->db->bindParameter("cpd_konto", "s", $array['cpd_konto']);
        $this->db->bindParameter("erloeskonto", "s", $array['erloeskonto']);
        $this->db->bindParameter("steuerkennzeichen", "s", $array['steuerkennzeichen']);
        $this->db->bindParameter("steuersatz", "s", $array['steuersatz']);
        $this->db->bindParameter("ansprechpartner", "s", $array['ansprechpartner']);
        $this->db->bindParameter("tel_ansprechpartner", "i", $array['ansprechpartner_tel']);
        $this->db->bindParameter("organisationseinheit", "s", $array['organisationseinheit']);
        $this->db->bindParameter("mail_ansprechpartner", "s", $array['ansprechpartner_mail']);
        $this->db->bindParameter("standardbetrag", "s", $array['standardbetrag']);
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        return $this->basePdbinsert($this->table);
    }
    
    /**
     * An Event with certain id will be updated
     * @param int $id
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function saveEntity($id, $array)
    {
        $this->db->setStatement("UPDATE t:".$this->table." SET buchungskreis = :buchungskreis, v_schluessel = :v_schluessel, auftragsnr = :auftragsnr, bezeichnung = :bezeichnung, v_land = :v_land, v_ort = :v_ort, anmeldefrist_beginn = :anmeldefrist_beginn, anmeldefrist_ende = :anmeldefrist_ende, v_beginn = :v_beginn, v_ende = :v_ende, cpd_konto = :cpd_konto, erloeskonto = :erloeskonto, steuerkennzeichen = :steuerkennzeichen, steuersatz = :steuersatz, ansprechpartner = :ansprechpartner, ansprechpartner_tel = :tel_ansprechpartner, organisationseinheit = :organisationseinheit, ansprechpartner_mail = :mail_ansprechpartner, standardbetrag = :standardbetrag, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("buchungskreis", "s", $array['buchungskreis']);
        $this->db->bindParameter("v_schluessel", "s", $array['v_schluessel']);
        $this->db->bindParameter("auftragsnr", "s", $array['auftragsnr']);
        $this->db->bindParameter("bezeichnung", "s", $array['bezeichnung']);
        $this->db->bindParameter("v_land", "s", $array['v_land']);
        $this->db->bindParameter("v_ort", "s", $array['v_ort']);
        $this->db->bindParameter("anmeldefrist_beginn", "i", $array['anmeldefrist_beginn']);
        $this->db->bindParameter("anmeldefrist_ende", "i", $array['anmeldefrist_ende']);
        $this->db->bindParameter("v_beginn", "i", $array['v_beginn']);
        $this->db->bindParameter("v_ende", "i", $array['v_ende']);
        $this->db->bindParameter("cpd_konto", "s", $array['cpd_konto']);
        $this->db->bindParameter("erloeskonto", "s", $array['erloeskonto']);
        $this->db->bindParameter("steuerkennzeichen", "s", $array['steuerkennzeichen']);
        $this->db->bindParameter("steuersatz", "s", $array['steuersatz']);
        $this->db->bindParameter("ansprechpartner", "s", $array['ansprechpartner']);
        $this->db->bindParameter("tel_ansprechpartner", "i", $array['ansprechpartner_tel']);
        $this->db->bindParameter("organisationseinheit", "s", $array['organisationseinheit']);
        $this->db->bindParameter("mail_ansprechpartner", "s", $array['ansprechpartner_mail']);
        $this->db->bindParameter("standardbetrag", "s", $array['standardbetrag']);
        $this->db->bindParameter("last_date", "s", date("YmdHis"));
        return $this->basePdbquery();
    }

    /**
     * The existance of fab_tagungen will be checked and created if the table is missing
     * @return true/exception
     */
    public function createTable()
    {
        $table_create_statement = "id int(11) NOT NULL AUTO_INCREMENT,
                                  buchungskreis varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_schluessel varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  auftragsnr varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  bezeichnung varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_ort varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  anmeldefrist_beginn int(8) NOT NULL,
                                  anmeldefrist_ende int(8) NOT NULL,
                                  v_beginn int(8) NOT NULL,
                                  v_ende int(8) NOT NULL,
                                  cpd_konto varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  erloeskonto varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  steuerkennzeichen varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  steuersatz varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner_tel varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  organisationseinheit varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner_mail varchar(241) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  stellvertreter_mail varchar(241) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  standardbetrag varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date bigint(14) NOT NULL,
                                  last_date bigint(14) NOT NULL,
                                  PRIMARY KEY (id),
                                  UNIQUE KEY v_schluessel (v_schluessel) ";
        
        return $this->baseCreateTable($this->table, $table_create_statement);
        $this->updateTable();
    }
    
    /**
     * Execute changes for the table fab_tagungen
     * @return boolean
     */
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
}