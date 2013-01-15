<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabRepository as fabRepository;
use \Fab\Domain\Participant\Object\participant as participant;
use \LWddd\ValueObject as ValueObject;
use \Fab\Domain\Participant\Specification\isDeletable as isDeletable;

class participantRepository extends fabRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $this->commandHandler = new participantCommandHandler($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $this->queryHandler = new participantQueryHandler($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
    
    protected function buildParticipantObjectByArray($data, $dirty=false)
    {
        $participant = new participant($data['id']);
        $participant->setDataValueObject(new ValueObject($data));
        $participant->setLoaded();
        if ($dirty===true) {
            $participant->setDirty();
        } 
        else {
            $participant->unsetDirty();
        }
        return $participant;
    }
    
    public function getParticipantObjectById($id, $dirty=false)
    {
        $data = $this->getQueryHandler()->loadParticipantById($id);
        return $this->buildParticipantObjectByArray($data, $dirty=false);
    }
    
    public function getParticipantsAggregateByEventId($eventId, $dirty=false)
    {
        $items = $this->getQueryHandler()->loadParticipantsByEventId($eventId);
        foreach($items as $item) {
             $entities[] =  $this->buildParticipantObjectByArray($item, $dirty=false);
        }
        return new \LWddd\EntityAggregate($entities);
    }
    
    public function saveParticipant($eventId, participant $participant)
    {
        if ($participant->getId() > 0 ) {
            $result = $this->getCommandHandler()->saveEntity($participant->getId(), $participant->getValues());
            $id = $participant->getId();
        }
        else {
            $result = $this->getCommandHandler()->addEntity($eventId, $participant->getValues());
            $id = $result;
        }
        if ($result) {
            $participant->setLoaded();
            $participant->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $participant->setLoaded();
            }
            else {
                $participant->unsetLoaded();
            }
            $participant->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }
        return $id;
    }
    
    public function deleteParticipantById($id)
    {
        $participant = $this->getParticipantObjectById($id);
        if (isDeletable::getInstance()->isSatisfiedBy($participant)) {
            return $this->getCommandHandler()->deleteEntityById($id);
        }
        else {
            throw new Exception('Delete not allowed, because Participant was already submitted to SAP!');
        }
    }

    public function getParticipantsAggregateByCsvFile($csvFile)
    {
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 2000, ";", '"')) !== FALSE) {
                if ($data[0]>400000) {
                    $array['id'] = intval($data[0]-400000);
                }
                else {
                    $array['id'] = false;
                }
                $array['unternehmenshortcut'] = $data[1];
                $array['unternehmen'] = $data[2];
                $array['institut'] = $data[3];
                $array['strasse'] = $data[4];
                $array['plz'] = $data[5];
                $array['ort'] = $data[6];
                $array['land'] = $data[7];
                $array['anrede'] = $data[8];
                $array['titel'] = $data[9];
                $array['vorname'] = $data[10];
                $array['nachname'] = $data[11];
                $array['mail'] = $data[12];
                $array['teilnehmer_intern'] = $data[13];
                $array['sprache'] = $data[14];
                $array['ust_id_nr'] = $data[15];
                $array['betrag'] = $data[16];
                $array['zahlweise'] = $data[17];
                $array['auftragsnr'] = $data[18];
                $array['v_schluessel'] = $data[19];
                $entities[] =  $this->buildParticipantObjectByArray($array, true);
            }
            fclose($handle);
            return new \LWddd\EntityAggregate($entities);
        }
        else {
            throw new \Exception("Could'nt open CSV File!");
        }
    }    
    
    public function saveCsvData($eventId, $aggregate)
    {
        foreach($aggregate as $entity) {
            $id = $this->getQueryHandler()->getParticipantIdByEventIdAndFirstnameAndLastnameAndEmail($eventId, $entity->getValueByKey('vorname'), $entity->getValueByKey('nachname'), $entity->getValueByKey('mail'));
            if ($id>0) {
                $ok = $this->getCommandHandler()->saveEntity($id, $entity->getValues());
            }
            else {
                $ok = $this->getCommandHandler()->addEntity($eventId, $entity->getValues());
            }
            if (!$ok) {
                throw new \Exception("Error saving CSV data for ".$entity->getValueByKey("mail"));
            }
        }
    }
}