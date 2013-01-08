<?php

namespace Fab\Domain\Text\Object;
use \Fab\Domain\Text\Object\text as text;
use \Fab\Domain\Text\Object\textData as textData;

class textAggregateFactory
{
    static public function buildAggregateFromDomainText($domainText, $queryHandler) 
    {
        $items = array();
        $cmd = $domainText->getTextName();
        if ($cmd == "showListAction") {
            $items = $queryHandler->getAllTexts();
        }
        foreach($items as $item) {
             $dummy = new text($item['id']);
             $dummy->setDataValueObject(new textData($item));
             $dummy->setLoaded();
             $dummy->unsetDirty();
             $entities[] = $dummy;
        }
        return new \LWddd\EntityAggregate($entities);
    }
}