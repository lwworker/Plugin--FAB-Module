<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class participantQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table = "fab_teilnehmer";
    }
    
    /**
     * Returns a list of all paticipants of a certain event
     * @param int $event_id
     * @return array
     */
    public function loadParticipantsByEventId($eventId)
    {
        if (intval($eventId)>0) {
            return $this->baseLoadEntriesByAttributeWithOrder("fab_teilnehmer", "event_id", "i", $eventId, "nachname", "ASC");
        }
        else {
            throw new \Exception("no valid EventId available!");
        }
    }

    /**
     * Returns all saved data for a specific participant
     * @param int $id
     * @return array
     */
    public function loadParticipantById($id)
    {
        return $this->baseGetEntryById($id, "fab_teilnehmer");
    }
    
    public function checkParticipantByEventIdAndFirstnameAndLastnameAndEmail($eventId, $firstname, $lastname, $email) 
    {
        $sql = "SELECT id FROM ".$this->table." WHERE event_id = '".intval($eventId)."' AND vorname = '".$this->db->quote(trim($firstname))."' AND nachname = '".$this->db->quote(trim($lastname))."' AND mail = '".$this->db->quote(trim($email))."'";
        //die($sql);
        $result = $this->db->select1($sql);
        if ($result['id'] > 0) {
            return true;
        }
        return false;
    }
}