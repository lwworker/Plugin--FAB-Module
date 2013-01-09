<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include_once(dirname(__FILE__) . '/../../../../Services/Autoloader/fabAutoloader.php');
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_registry.class.php';

/**
 * Test class for eventValidate.
 * Generated by PHPUnit on 2013-01-03 at 11:48:05.
 */
class textCommandHandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var eventValidate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $db = new lw_db_mysqli("root", "", "localhost", "fab_test");
        $db->connect();
        $this->db = $db;
        
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig(array("plugins" => "C:/xampp/htdocs/c38/contentory/c_server/plugins/",
                                     "plugin_path" => array ("lw" => "C:/xampp/htdocs/c38/contentory/c_server/modules/lw/")));
        $this->textCommandHandler = new Fab\Domain\Text\Model\textCommandHandler($this->db);
        $this->textCommandHandler->setDebug(false);        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        #$this->db->setStatement("DROP TABLE t:fab_tagungen ");
        #$this->db->pdbquery();
    }

    /**
     * @todo Implement test().
     */
    public function test()
    {
        
    }
    
    public function getInstace($array)
    {
        return new \LWddd\ValueObject($array);
    }

}

?>
