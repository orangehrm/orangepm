<?php

require_once 'PHPUnit/Framework.php';
define('SF_APP_NAME', 'orangepm');
define('SF_ENV', 'test');
define('SF_CONN', 'doctrine');

if (!defined('TEST_ENV_CONFIGURED')) {

    require_once(dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');
    AllTests::$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP_NAME, SF_ENV, true);
    sfContext::createInstance(AllTests::$configuration);

    define('TEST_ENV_CONFIGURED', TRUE);
}

class AllTests {

    public static $configuration = null;
    public static $databaseManager = null;
    public static $connection = null;

    protected function setUp() {

        if (self::$configuration) {
            // initialize database manager
            self::$databaseManager = new sfDatabaseManager(self::$configuration);
            self::$databaseManager->loadConfiguration();

            if (SF_CONN != '') {
                self::$connection = self::$databaseManager->getDatabase(SF_CONN);
            }
        }
           
    }

    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');

        $suite->addTestFile(dirname(__FILE__) . '/ProjectProgressTest.php');
        
        return $suite;
    }

}
