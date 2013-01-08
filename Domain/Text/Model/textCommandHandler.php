<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;

class textCommandHandler
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

    public function addText(ValueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_tagungen ( buchungskreis, v_schluessel, auftragsnr, bezeichnung, v_land, v_ort, anmeldefrist_beginn, anmeldefrist_ende, v_beginn, v_ende, cpd_konto, erloeskonto, steuerkennzeichen, steuersatz, ansprechpartner, ansprechpartner_tel, organisationseinheit, ansprechpartner_mail, stellvertreter_mail, standardbetrag, first_date, last_date ) VALUES ( :buchungskreis, :v_schluessel, :auftragsnr, :bezeichnung, :v_land, :v_ort, :anmeldefrist_beginn, :anmeldefrist_ende, :v_beginn, :v_ende, :cpd_konto, :erloeskonto, :steuerkennzeichen, :steuersatz, :ansprechpartner, :tel_ansprechpartner, :organisationseinheit, :mail_ansprechpartner, :stellvertreter_mail, :standardbetrag, :first_date, :last_date ) ");
        $this->db->bindParameter("buchungskreis", "s", $entity->getValueByKey('buchungskreis'));
        $this->db->bindParameter("v_schluessel", "s", $entity->getValueByKey('v_schluessel'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("bezeichnung", "s", $entity->getValueByKey('bezeichnung'));
        $this->db->bindParameter("v_land", "s", $entity->getValueByKey('v_land'));
        $this->db->bindParameter("v_ort", "s", $entity->getValueByKey('v_ort'));
        $this->db->bindParameter("anmeldefrist_beginn", "i", $entity->getValueByKey('anmeldefrist_beginn'));
        $this->db->bindParameter("anmeldefrist_ende", "i", $entity->getValueByKey('anmeldefrist_ende'));
        $this->db->bindParameter("v_beginn", "i", $entity->getValueByKey('v_beginn'));
        $this->db->bindParameter("v_ende", "i", $entity->getValueByKey('v_ende'));
        $this->db->bindParameter("cpd_konto", "s", $entity->getValueByKey('cpd_konto'));
        $this->db->bindParameter("erloeskonto", "s", $entity->getValueByKey('erloeskonto'));
        $this->db->bindParameter("steuerkennzeichen", "s", $entity->getValueByKey('steuerkennzeichen'));
        $this->db->bindParameter("steuersatz", "s", $entity->getValueByKey('steuersatz'));
        $this->db->bindParameter("ansprechpartner", "s", $entity->getValueByKey('ansprechpartner'));
        $this->db->bindParameter("tel_ansprechpartner", "i", $entity->getValueByKey('ansprechpartner_tel'));
        $this->db->bindParameter("organisationseinheit", "s", $entity->getValueByKey('organisationseinheit'));
        $this->db->bindParameter("mail_ansprechpartner", "s", $entity->getValueByKey('ansprechpartner_mail'));
        $this->db->bindParameter("stellvertreter_mail", "s", $entity->getValueByKey('stellvertreter_mail'));
        $this->db->bindParameter("standardbetrag", "s", $entity->getValueByKey('standardbetrag'));
        $this->db->bindParameter("first_date", "i", $entity->getValueByKey('first_date'));
        $this->db->bindParameter("last_date", "i", $entity->getValueByKey('last_date'));
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $newId = $this->db->pdbinsert($this->db->gt('fab_tagungen'));
            if ($newId > 0) {
                return $newId;
            }
            else {
                throw new \Exception('...'); 
            }
        }
    }
    
    public function saveText($id, ValueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_tagungen SET buchungskreis = :buchungskreis, v_schluessel = :v_schluessel, auftragsnr = :auftragsnr, bezeichnung = :bezeichnung, v_land = :v_land, v_ort = :v_ort, anmeldefrist_beginn = :anmeldefrist_beginn, anmeldefrist_ende = :anmeldefrist_ende, v_beginn = :v_beginn, v_ende = :v_ende, cpd_konto = :cpd_konto, erloeskonto = :erloeskonto, steuerkennzeichen = :steuerkennzeichen, steuersatz = :steuersatz, ansprechpartner = :ansprechpartner, ansprechpartner_tel = :tel_ansprechpartner, organisationseinheit = :organisationseinheit, ansprechpartner_mail = :mail_ansprechpartner, stellvertreter_mail = :stellvertreter_mail, standardbetrag = :standardbetrag, first_date = :first_date, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("buchungskreis", "s", $entity->getValueByKey('buchungskreis'));
        $this->db->bindParameter("v_schluessel", "s", $entity->getValueByKey('v_schluessel'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("bezeichnung", "s", $entity->getValueByKey('bezeichnung'));
        $this->db->bindParameter("v_land", "s", $entity->getValueByKey('v_land'));
        $this->db->bindParameter("v_ort", "s", $entity->getValueByKey('v_ort'));
        $this->db->bindParameter("anmeldefrist_beginn", "i", $entity->getValueByKey('anmeldefrist_beginn'));
        $this->db->bindParameter("anmeldefrist_ende", "i", $entity->getValueByKey('anmeldefrist_ende'));
        $this->db->bindParameter("v_beginn", "i", $entity->getValueByKey('v_beginn'));
        $this->db->bindParameter("v_ende", "i", $entity->getValueByKey('v_ende'));
        $this->db->bindParameter("cpd_konto", "s", $entity->getValueByKey('cpd_konto'));
        $this->db->bindParameter("erloeskonto", "s", $entity->getValueByKey('erloeskonto'));
        $this->db->bindParameter("steuerkennzeichen", "s", $entity->getValueByKey('steuerkennzeichen'));
        $this->db->bindParameter("steuersatz", "s", $entity->getValueByKey('steuersatz'));
        $this->db->bindParameter("ansprechpartner", "s", $entity->getValueByKey('ansprechpartner'));
        $this->db->bindParameter("tel_ansprechpartner", "i", $entity->getValueByKey('ansprechpartner_tel'));
        $this->db->bindParameter("organisationseinheit", "s", $entity->getValueByKey('organisationseinheit'));
        $this->db->bindParameter("mail_ansprechpartner", "s", $entity->getValueByKey('ansprechpartner_mail'));
        $this->db->bindParameter("stellvertreter_mail", "s", $entity->getValueByKey('stellvertreter_mail'));
        $this->db->bindParameter("standardbetrag", "s", $entity->getValueByKey('standardbetrag'));
        $this->db->bindParameter("first_date", "i", $entity->getValueByKey('first_date'));
        $this->db->bindParameter("last_date", "i", $entity->getValueByKey('last_date'));
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $ok = $this->db->pdbquery();
            if ($ok) {
                return $entity;
            }
            else {
                throw new \Exception('...'); 
            }
        }
    }
    
    public function deleteText(Entity $entity)
    {
        if ($entity->isDeleteable() && $entity->getId() > 0) {
            $this->db->setStatement("DELETE FROM t:fab_tagungen WHERE id = :id ");
            $this->db->bindParameter("id", "i", $entity->getId());
            if($this->debug == true){
                die($this->db->prepare());
            }else{
                $ok = $this->db->pdbquery();
                if ($ok) {
                    return $entity;
                }
                else {
                    throw new \Exception('...'); 
                }
            }
        }
        else { 
            throw new \Exception('...'); 
        }
    }
    
    public function createTable()
    {
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
        if($bool === true) {
            $this->debug = true;
        }
        else {
            $this->debug = false;
        }
    }
}