<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class participantQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    
    /**
     * Returns a list of all paticipants of a certain event
     * @param int $event_id
     * @return array
     */
    public function loadParticipantsByEvent($event_id)
    {
        $this->baseLoadEntriesByAttributeWithOrder("fab_teilnehmer", "event_id", "i", $event_id, "nachname", "ASC");
    }

    /**
     * Returns all saved data for a specific participant
     * @param int $id
     * @return array
     */
    public function loadParticipantById($id)
    {
        $this->baseGetEntryById($id, "fab_teilnehmer");
    }
}