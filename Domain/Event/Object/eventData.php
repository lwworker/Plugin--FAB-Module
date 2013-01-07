<?php

namespace Fab\Domain\Event\Object;

class eventData extends \LWddd\ValueObject
{
    public function __construct($values)
    {
        $allowedKeys = array(
                "id", 
                "buchungskreis", 
                "v_schluessel", 
                "auftragsnr", 
                "bezeichnung", 
                "v_land", 
                "v_ort", 
                "anmeldefrist_beginn", 
                "anmeldefrist_ende", 
                "v_beginn", 
                "v_ende", 
                "cpd_konto", 
                "erloeskonto", 
                "steuerkennzeichen", 
                "steuersatz", 
                "ansprechpartner", 
                "ansprechpartner_tel", 
                "organisationseinheit", 
                "ansprechpartner_mail", 
                "stellvertreter_mail", 
                "standardbetrag", 
                "first_date", 
                "last_date");
        
        parent::__construct($values, $allowedKeys, new \Fab\Domain\Event\Service\eventValidate());
    }
    

}
