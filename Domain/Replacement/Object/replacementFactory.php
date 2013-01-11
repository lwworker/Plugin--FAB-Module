<?php

namespace Fab\Domain\Replacement\Object;
use \Fab\Library\fabDIC as DIC;
use \Fab\Domain\Replacement\Object\replacementData as replacementData;

class replacementFactory 
{
    public static function buildReplacementByEventId($id)
    {
        $dic = new DIC();        
        $data = $dic->getReplacementQueryHandler()->getReplacementByEventId($id);
        $replacement = new replacement();
        $replacement->setDataValueObject(new replacementData($data));
        $replacement->setLoaded();
        $replacement->unsetDirty();
        return $replacement;
    }
}