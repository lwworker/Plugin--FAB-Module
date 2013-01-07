<?php
class zipcheck
{
    public function __construct() {
        
    }
        
    public function check($nation_shortcut,$zip)
    {
        $country = $this->getCountry($nation_shortcut);
        if($country != false){
            switch ($country["checktyp"]){
                case 1:
                    $result = $this->checkTyp1($zip, $country);
                    break;
                case 2:
                    $result = $this->checkTyp2($zip, $country);
                    break;
                case 3:
                    $result = $this->checkTyp3($zip, $country);
                    break;
                case 4:
                    $result = $this->checkTyp4($zip, $country);
                    break;
                case 5:
                    $result = $this->checkTyp5($zip, $country);
                    break;
                case 6:
                    $result = $this->checkTyp6($zip, $country);
                    break;
                case 7:
                    $result = $this->checkTyp7($zip, $country);
                    break;
                case 8:
                    $result = $this->checkTyp8($zip, $country);
                    break;
                case 9:
                    $result = $this->checkTyp9($zip, $country);
                    break;
            }
            
            if($result){
                return 1;
            }else{
                return 0;
            }
        }
        return -1;
    }
    
/*-----------------------------------------------------------
    | Pruefungsmethoden:                                    |
    |    1 Länge Maximalwert, lückenlos                     |
    |    2 Länge Maximalwert, numerisch, lückenlos          |
    |    3 Länge exakt einzuhalten, lückenlos               |
    |    4 Länge exakt einzuhalten, numerisch, lückenlos    |
    |    5 Länge Maximalwert                                |
    |    6 Länge Maximalwert, numerisch                     |
    |    7 Länge exakt einzuhalten                          |
    |    8 Länge exakt einzuhalten, numerisch               |
    |    9 Prüfung gegen landesspezifische Schablone        |
-----------------------------------------------------------*/    
    
    private function checkTyp1($zip,$country)
    {
        if(!$this->isAlnumGapless($zip)){
            return false;
        }
        if(!$this->lesserOrEqualToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp2($zip,$country)
    {
        if(!$this->lesserOrEqualToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        if(!ctype_digit($zip)){
            return false;
        }
        return true;
    }
    
    private function checkTyp3($zip,$country)
    {
        if(!$this->isAlnumGapless($zip)){
            return false;
        }
        if(!$this->equalToMaxLenght(strlen($zip),$country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp4($zip,$country)
    {
        if(!$this->equalToMaxLenght(strlen($zip),$country["maxvalue"])){
            return false;
        }
        if(!ctype_digit($zip)){
            return false;
        }
        return true;
    }
    
    private function checkTyp5($zip,$country)
    {
        if(!$this->isAlnumWithGaps($zip)){
            return false;
        }
        if(!$this->lesserOrEqualToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp6($zip,$country)
    {
        if(!$this->isNumericWithGaps($zip)){
            return false;
        }
        if(!$this->lesserOrEqualToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp7($zip,$country)
    {
        if(!$this->isAlnumWithGaps($zip)){
            return false;
        }
        if(!$this->equalToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp8($zip,$country)
    {
        if(!$this->isNumericWithGaps($zip)){
            return false;
        }
        if(!$this->equalToMaxLenght(strlen($zip), $country["maxvalue"])){
            return false;
        }
        return true;
    }
    
    private function checkTyp9($zip,$country)
    {
        if(strlen($zip) != strlen($country["pattern"])){
            return false;
        }
        for($i = 0; $i <= strlen($zip) - 2 ;$i++){
            $a = substr($zip, $i, 1);
            $b = substr($country["pattern"], $i, 1);
            
            if($b == "N"){
                if(!ctype_digit($a)){
                    return false;
                }
            }elseif ($b == "A") {
                if(!ctype_alpha($a)){
                    return false;
                }
            }else{
                if($a != $b){
                    return false;
                }
            }
        }
        return true;
        
    }
    
    private function isAlnumWithGaps($zip) {
        $a = preg_replace('/[^a-zA-Z0-9 -]/', '',$zip);
        if ($zip == $a){
            return true;
        }
        return false;
    }
    
    private function isAlnumGapless($zip) {
        $a = preg_replace('/[^a-zA-Z0-9-]/', '',$zip);
        if ($zip == $a){
            return true;
        }
        return false;
    }
    
    private function isNumericWithGaps($zip){
        $a = preg_replace('/[^0-9 -]/', '',$zip);
        if ($zip == $a){
            return true;
        }
        return false;
    }
    
    private function lesserOrEqualToMaxLenght($a,$b)
    {
        if($a > $b){
            return false;
        }
        return true;
    }
    
    private function equalToMaxLenght($a,$b)
    {
        if($a != $b){
            return false;
        }
        return true;
    }
 
    function getCountry($country)
    {
        $countries["CL"] = array("name" =>"Chile","maxvalue" => 7, "checktyp" => 1);
        $countries["AU"] = array("name" =>"Australien","maxvalue" => 7, "checktyp" => 2);
        $countries["FO"] = array("name" =>utf8_decode("Färöer"),"maxvalue" => 3, "checktyp" => 4);
        $countries["IS"] = array("name" =>"Island","maxvalue" => 3, "checktyp" => 4);
        $countries["LS"] = array("name" =>"Lesotho","maxvalue" => 3, "checktyp" => 4);
        $countries["TW"] = array("name" =>"Taiwan","maxvalue" => 3, "checktyp" => 4);
        $countries["AR"] = array("name" =>"Argentinien","maxvalue" => 4, "checktyp" => 4);
        $countries["AT"] = array("name" =>utf8_decode("Österreich"),"maxvalue" => 4, "checktyp" => 4);
        $countries["BE"] = array("name" =>"Belgien","maxvalue" => 4, "checktyp" => 4);
        $countries["CH"] = array("name" =>"Schweiz","maxvalue" => 4, "checktyp" => 4);
        $countries["CR"] = array("name" =>"Costa Rica","maxvalue" => 4, "checktyp" => 4);
        $countries["CY"] = array("name" =>"Zypern","maxvalue" => 4, "checktyp" => 4);
        $countries["DK"] = array("name" =>utf8_decode("Dänemark"),"maxvalue" => 4, "checktyp" => 4);
        $countries["HU"] = array("name" =>"Ungarn","maxvalue" => 4, "checktyp" => 4);
        $countries["LI"] = array("name" =>"Liechtenstein","maxvalue" => 4, "checktyp" => 4);
        $countries["LU"] = array("name" =>"Luxemburg","maxvalue" => 4, "checktyp" => 4);
        $countries["NO"] = array("name" =>"Norwegen","maxvalue" => 4, "checktyp" => 4);
        $countries["NZ"] = array("name" =>"Neuseeland","maxvalue" => 4, "checktyp" => 4);
        $countries["PH"] = array("name" =>"Philippinen","maxvalue" => 4, "checktyp" => 4);
        $countries["PT"] = array("name" =>"Portugal","maxvalue" => 4, "checktyp" => 4);
        $countries["SI"] = array("name" =>"Slowenien","maxvalue" => 4, "checktyp" => 4);
        $countries["TN"] = array("name" =>"Tunesien","maxvalue" => 4, "checktyp" => 4);
        $countries["VE"] = array("name" =>"Venezuela","maxvalue" => 4, "checktyp" => 4);
        $countries["ZA"] = array("name" =>utf8_decode("Südafrika"),"maxvalue" => 4, "checktyp" => 4);
        $countries["DE"] = array("name" =>"Deutschland","maxvalue" => 5, "checktyp" => 4);
        $countries["DZ"] = array("name" =>"Algerien","maxvalue" => 5, "checktyp" => 4);
        $countries["ES"] = array("name" =>"Spanien","maxvalue" => 5, "checktyp" => 4);
        $countries["FI"] = array("name" =>"Finnland","maxvalue" => 5, "checktyp" => 4);
        $countries["FR"] = array("name" =>"Frankreich","maxvalue" => 5, "checktyp" => 4);
        $countries["GR"] = array("name" =>"Griechenland","maxvalue" => 5, "checktyp" => 4);
        $countries["HR"] = array("name" =>"Kroatien","maxvalue" => 5, "checktyp" => 4);
        $countries["ID"] = array("name" =>"Indonesien","maxvalue" => 5, "checktyp" => 4);
        $countries["IL"] = array("name" =>"Israel","maxvalue" => 5, "checktyp" => 4);
        $countries["IR"] = array("name" =>"Iran","maxvalue" => 5, "checktyp" => 4);
        $countries["IT"] = array("name" =>"Italien","maxvalue" => 5, "checktyp" => 4);
        $countries["KW"] = array("name" =>"Kuwait","maxvalue" => 5, "checktyp" => 4);
        $countries["MC"] = array("name" =>"Monaco","maxvalue" => 5, "checktyp" => 4);
        $countries["MX"] = array("name" =>"Mexiko","maxvalue" => 5, "checktyp" => 4);
        $countries["MY"] = array("name" =>"Malaysia","maxvalue" => 5, "checktyp" => 4);
        $countries["SA"] = array("name" =>"Saudi-Arabien","maxvalue" => 5, "checktyp" => 4);
        $countries["TH"] = array("name" =>"Thailand","maxvalue" => 5, "checktyp" => 4);
        $countries["TR"] = array("name" =>utf8_decode("Türkei"),"maxvalue" => 5, "checktyp" => 4);
        $countries["UA"] = array("name" =>"Ukraine","maxvalue" => 5, "checktyp" => 4);
        $countries["VN"] = array("name" =>"Vietnam","maxvalue" => 5, "checktyp" => 4);
        $countries["YU"] = array("name" =>"Jugoslawien","maxvalue" => 5, "checktyp" => 4);
        $countries["CN"] = array("name" =>"China","maxvalue" => 6, "checktyp" => 4);
        $countries["IN"] = array("name" =>"Indien","maxvalue" => 6, "checktyp" => 4);
        $countries["KZ"] = array("name" =>"Kasachstan","maxvalue" => 6, "checktyp" => 4);
        $countries["NP"] = array("name" =>"Nepal","maxvalue" => 6, "checktyp" => 4);
        $countries["RU"] = array("name" =>"Russische Foed.","maxvalue" => 6, "checktyp" => 4);
        $countries["SG"] = array("name" =>"Singapur","maxvalue" => 6, "checktyp" => 4);
        $countries["RO"] = array("name" =>utf8_decode("Rumänien"),"maxvalue" => 7, "checktyp" => 4);
        $countries["KP"] = array("name" =>"Nordkorea","maxvalue" => 7, "checktyp" => 5);
        $countries["GB"] = array("name" =>"Verein. Königr.","maxvalue" => 9, "checktyp" => 5);
        $countries["JP"] = array("name" =>"Japan","maxvalue" => 7, "checktyp" => 6);
        $countries["KR"] = array("name" =>utf8_decode("Südkorea"),"maxvalue" => 7, "checktyp" => 6);
        $countries["BR"] = array("name" =>"Brasilien","maxvalue" => 9, "checktyp" => 7);
        $countries["CA"] = array("name" =>"Kanada","maxvalue" => 6, "checktyp" => 9, "pattern" => "ANA NAN");
        $countries["CZ"] = array("name" =>"Tschechische Re","maxvalue" => 6, "checktyp" => 9, "pattern" => "NNN NN");
        $countries["NL"] = array("name" =>"Niederlande","maxvalue" => 6, "checktyp" => 9, "pattern" => "NNNN AA");
        $countries["PL"] = array("name" =>"Polen","maxvalue" => 6, "checktyp" => 9, "pattern" => "NN-NNN");
        $countries["SE"] = array("name" =>"Schweden","maxvalue" => 6, "checktyp" => 9, "pattern" => "NN-NNN");
        $countries["SK"] = array("name" =>"Slowakei","maxvalue" => 6, "checktyp" => 9, "pattern" => "NNN NN");
        
        if(array_key_exists($country, $countries)){
            return $countries[$country];
        }
        return false;
        
    }
    
    public function test($zip,$country)
    {
        switch ($country["checktyp"]){
            case 1:
                $result = $this->checkTyp1($zip, $country);
                break;
            case 2:
                $result = $this->checkTyp2($zip, $country);
                break;
            case 3:
                $result = $this->checkTyp3($zip, $country);
                break;
            case 4:
                $result = $this->checkTyp4($zip, $country);
                break;
            case 5:
                $result = $this->checkTyp5($zip, $country);
                break;
            case 6:
                $result = $this->checkTyp6($zip, $country);
                break;
            case 7:
                $result = $this->checkTyp7($zip, $country);
                break;
            case 8:
                $result = $this->checkTyp8($zip, $country);
                break;
            case 9:
                $result = $this->checkTyp9($zip, $country);
                break;
        }

        if($result){
            return 1;
        }else{
            return 0;
        }
        return -1;
    }
}
?>