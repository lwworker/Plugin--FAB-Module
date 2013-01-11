<?php

namespace Fab\Domain\Participant\Object;
use \LWddd\ValueObject as ValueObject;
use \Fab\Library\fabDIC as DIC;

class participantData extends ValueObject
{
    public function __construct($values)
    {
        $this->dic = new DIC();
        $allowedKeys = array(
                "id", 
                "event_id", 
                "anrede", 
                "sprache", 
                "titel", 
                "nachname", 
                "vorname", 
                "institut", 
                "unternehmen", 
                "strasse", 
                "plz", 
                "ort", 
                "land", 
                "mail", 
                "ust_id_nr", 
                "zahlweise", 
                "teilnehmer_intern", 
                "betrag", 
                "first_date", 
                "last_date");
        
        parent::__construct($values, $allowedKeys, $this->dic->getParticipantValidationObject());
    }
}