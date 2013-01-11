<?php

namespace Fab\Domain\Replacement\Model;
use \LWddd\ValueObject as ValueObject;
use \Fab\Library\fabCommandHandler as fabCommandHandler;

class replacementCommandHandler extends fabCommandHandler
{
    public function __construct(\lw_db $db)
    {
        parent::__construct($db);
    }
        
    public function saveReplacementByEventId($eventId, $value)
    {
        $this->db->setStatement("UPDATE t:fab_tagungen SET stellvertreter_mail = :stellvertreter_mail WHERE id = :id ");
        $this->db->bindParameter("id", "i", $eventId);
        $this->db->bindParameter("stellvertreter_mail", "s", $value);
        return $this->basePdbquery();
    }
}
