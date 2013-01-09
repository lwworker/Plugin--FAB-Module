<?php 
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once dirname(__FILE__) . "/Domain/Country/Model/countryCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Country/Model/countryQueryHandlerTest.php";

require_once dirname(__FILE__) . "/Domain/Event/Model/eventCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Event/Model/eventQueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Event/Service/eventValidateTest.php";

require_once dirname(__FILE__) . "/Domain/Participant/Model/participantCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Participant/Model/participantQueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Participant/Service/participantValidateTest.php";

require_once dirname(__FILE__) . "/Domain/Text/Model/textCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Text/Model/textQueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Text/Service/textValidateTest.php";

require_once dirname(__FILE__) . "/Services/Zipcheck/zipcheckTest.php";

$testClassNames = array(
    "countryCommandHandlerTest",
    "countryQueryHandlerTest",
    
    "eventCommandHandlerTest",
    "eventQueryHandlerTest",
    "eventValidateTest",
    
    "participantCommandHandlerTest",
    "participantQueryHandlerTest",
    "participantValidateTest",
    
    "textCommandHandlerTest",
    "textQueryHandlerTest",
    "textValidateTest",
    
    "zipcheckTest"
    );

foreach ($testClassNames as $test) {
    $phpunit = new PHPUnit2_Framework_TestSuite($test);
    $phpunit->run();
}