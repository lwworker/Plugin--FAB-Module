<?php

namespace Fab\Domain\Participant\View;
use \LWddd\EntityAggregate as EntityAggregate;
use \LWddd\DomainEvent as DomainEvent;
use \lw_view as lw_view;
use \lw_page as lw_page;
use \Fab\Library\fabView as fabView;
use \Fab\Library\fabDIC as DIC;

class participantCsvDownload extends fabView
{
    public function __construct(DomainEvent $domainEvent, EntityAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
        $this->domainEvent = $domainEvent;
        $this->dic = new DIC();
    }
    
    public function filterOutput($value) 
    {
        $value = strip_tags($value);
        $value = str_replace('"', "'", $value);
        $value = str_replace(';', ',', $value);
        return $value;
    }
    
    public function render()
    {
        foreach($this->aggregate as $item) {
            if (!$event) {
                $event = $this->dic->getEventRepository()->getEventObjectById($item->getValueByKey("event_id"));
            }
            echo '"'.$this->filterOutput($item->getValueByKey("id")+400000).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("unternehmenshortcut")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("unternehmen")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("institut")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("strasse")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("plz")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("ort")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("land")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("anrede")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("titel")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("vorname")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("nachname")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("mail")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("teilnehmer_intern")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("sprache")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("ust_id_nr")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("betrag")).'";';
            echo '"'.$this->filterOutput($item->getValueByKey("zahlweise")).'";';
            echo '"'.$this->filterOutput($event->getValueByKey("auftragsnr")).'";';
            echo '"'.$this->filterOutput($event->getValueByKey("v_schluessel")).'"';
            echo "<br/>";
        }
        exit();
    }
}