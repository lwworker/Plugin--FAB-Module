<?php

namespace Fab\Domain\Participant\Model;

class participantCommandHandler
{
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry('db');
    }
    
    public function handle($domainEvent)
    {
        $command = $domainEvent->getEventName();
        $this->$command($domainEvent->getEntity());
    }
        
    public function addParticipant($event_id, \lw_ddd_valueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_teilnehmer ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, strasse, plz, ort, land, mail, veranstaltung, ust_id_nr, zahlweise, referenznr, teilnehmer_intern, auftragsnr, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :strasse, :plz, :ort, :land, :mail, :veranstaltung, :ust_id_nr, :zahlweise, :referenznr, :teilnehmer_intern, :auftragsnr, :betrag, :first_date, :last_date ) ");
        $this->db->bindParameter("event_id", "i", $event_id);
        $this->db->bindParameter("anrede", "s", $entity->getValueByKey('anrede'));
        $this->db->bindParameter("sprache", "s", $entity->getValueByKey('sprache'));
        $this->db->bindParameter("titel", "s", $entity->getValueByKey('titel'));
        $this->db->bindParameter("nachname", "s", $entity->getValueByKey('nachname'));
        $this->db->bindParameter("vorname", "s", $entity->getValueByKey('vorname'));
        $this->db->bindParameter("institut", "s", $entity->getValueByKey('institut'));
        $this->db->bindParameter("unternehmen", "s", $entity->getValueByKey('unternehmen'));
        $this->db->bindParameter("strasse", "s", $entity->getValueByKey('strasse'));
        $this->db->bindParameter("plz", "s", $entity->getValueByKey('plz'));
        $this->db->bindParameter("ort", "s", $entity->getValueByKey('ort'));
        $this->db->bindParameter("land", "s", $entity->getValueByKey('land'));
        $this->db->bindParameter("mail", "s", $entity->getValueByKey('mail'));
        $this->db->bindParameter("veranstaltung", "s", $entity->getValueByKey('veranstaltung'));
        $this->db->bindParameter("ust_id_nr", "s", $entity->getValueByKey('ust_id_nr'));
        $this->db->bindParameter("zahlweise", "s", $entity->getValueByKey('zahlweise'));
        $this->db->bindParameter("referenznr", "s", $entity->getValueByKey('referenznr'));
        $this->db->bindParameter("teilnehmer_intern", "i", $entity->getValueByKey('teilnehmer_intern'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("betrag", "s", $entity->getValueByKey('betrag'));
        $this->db->bindParameter("first_date", "i", $entity->getValueByKey('first_date'));
        $this->db->bindParameter("last_date", "i", $entity->getValueByKey('last_date'));
        
        if($this->debug == true) {
            die($this->db->prepare());
        }
        else {
            $newId = $this->db->pdbinsert($this->db->gt('fab_teilnehmer'));
            if ($newId > 0) {
                return $newId;
            }
            else {
                throw new \Exception('...'); 
            }
        }
    }
    
    public function saveParticipant($id, \lw_ddd_valueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_teilnehmer SET anrede = :anrede, sprache = :sprache, titel = :titel, nachname = :nachname, vorname = :vorname, institut = :institut, unternehmen = :unternehmen, strasse = :strasse, plz = :plz, ort = :ort, land = :land, mail = :mail, veranstaltung = :veranstaltung, ust_id_nr = :ust_id_nr, zahlweise = :zahlweise, referenznr = :referenznr, teilnehmer_intern = :teilnehmer_intern, auftragsnr = :auftragsnr, betrag = :betrag, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("anrede", "s", $entity->getValueByKey('anrede'));
        $this->db->bindParameter("sprache", "s", $entity->getValueByKey('sprache'));
        $this->db->bindParameter("titel", "s", $entity->getValueByKey('titel'));
        $this->db->bindParameter("nachname", "s", $entity->getValueByKey('nachname'));
        $this->db->bindParameter("vorname", "s", $entity->getValueByKey('vorname'));
        $this->db->bindParameter("institut", "s", $entity->getValueByKey('institut'));
        $this->db->bindParameter("unternehmen", "s", $entity->getValueByKey('unternehmen'));
        $this->db->bindParameter("strasse", "s", $entity->getValueByKey('strasse'));
        $this->db->bindParameter("plz", "s", $entity->getValueByKey('plz'));
        $this->db->bindParameter("ort", "s", $entity->getValueByKey('ort'));
        $this->db->bindParameter("land", "s", $entity->getValueByKey('land'));
        $this->db->bindParameter("mail", "s", $entity->getValueByKey('mail'));
        $this->db->bindParameter("veranstaltung", "s", $entity->getValueByKey('veranstaltung'));
        $this->db->bindParameter("ust_id_nr", "s", $entity->getValueByKey('ust_id_nr'));
        $this->db->bindParameter("zahlweise", "s", $entity->getValueByKey('zahlweise'));
        $this->db->bindParameter("referenznr", "s", $entity->getValueByKey('referenznr'));
        $this->db->bindParameter("teilnehmer_intern", "i", $entity->getValueByKey('teilnehmer_intern'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("betrag", "s", $entity->getValueByKey('betrag'));
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
    
    public function deleteEvent($id)
    {
        $this->db->setStatement("DELETE FROM t:fab_teilnehmer WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        if($this->debug == true){
            die($this->db->prepare());
        }else{
            $ok = $this->db->pdbquery();
            if (!$ok) {
                throw new \Exception('...'); 
            }
        }
    }

    public function createTable()
    {
        if(!$this->db->tableExits("fab_teilnehmer")){
            $this->db->setStatement("CREATE TABLE IF NOT EXISTS `fab_teilnehmer` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `event_id` int(11) NOT NULL,
                      `anrede` varchar(15) NOT NULL,
                      `sprache` varchar(2) NOT NULL,
                      `titel` varchar(20) NOT NULL,
                      `nachname` varchar(35) NOT NULL,
                      `vorname` varchar(35) NOT NULL,
                      `institut` varchar(35) NOT NULL,
                      `unternehmen` varchar(35) NOT NULL,
                      `strasse` varchar(30) NOT NULL,
                      `plz` varchar(10) NOT NULL,
                      `ort` varchar(35) NOT NULL,
                      `land` varchar(2) NOT NULL,
                      `mail` varchar(100) NOT NULL,
                      `veranstaltung` varchar(8) NOT NULL,
                      `ust_id_nr` varchar(20) NOT NULL,
                      `zahlweise` varchar(1) NOT NULL,
                      `referenznr` varchar(8) NOT NULL,
                      `teilnehmer_intern` int(1) NOT NULL,
                      `auftragsnr` varchar(12) NOT NULL,
                      `betrag` varchar(16) NOT NULL,
                      `first_date` int(14) NOT NULL,
                      `last_date` int(14) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
                ");
            if($this->debug == true){
                die($this->db->prepare());
            }else{
                $ok = $this->db->pdbquery();
                if(!$ok){
                    throw new \Exception('...'); 
                }
            }
        }
        $this->updateTable();
        
    }
    
    public function updateTable()
    {
        return true;
        /*
         * Wenn es noch keine Erweiterung gibt, dann true zurÃ¼ckgeben
         * FÃ¼r jede Erweiterung erst prÃ¼fen, ob die neue Spalte bereits vorhanden ist 
         * und wenn nicht, dann die Spalte mit "ALTER TABLE tablename ADD COLUMN ..." erstellen.
         * 
         */
    }
    
    public function setDebug($bool = true)
    {
        if($bool === true){
            $this->debug = true;
        }else{
            $this->debug = false;
        }
    }
    
}