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

    public function saveReplacement($id, $stellvertreter_mail)
    {
        $this->db->setStatement("UPDATE t:fab_tagungen SET stellvertreter_mail = :stellvertreter_mail WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("stellvertreter_mail", "s", $stellvertreter_mail);
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
                      `anrede` varchar(15) NOT NULL,
                      `sprache` varchar(2) NOT NULL,
                      `titel` varchar(20) NOT NULL,
                      `name` varchar(35) NOT NULL,
                      `institut` varchar(35) NOT NULL,
                      `unternehmen` varchar(35) NOT NULL,
                      `straÃŸe` varchar(30) NOT NULL,
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
