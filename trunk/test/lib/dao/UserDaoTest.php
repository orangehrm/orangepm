<?php
/**
 * Description of UserDaoTest
 *
 * @author guruce
 */
require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class UserDaoTest extends PHPUnit_Framework_TestCase{
    private $userDao;

    public function setup() {
        TestDataService::truncateTables(array('User','Project','ProjectUser'));
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/UserDao.yml');
        $this->userDao = new UserDao();
    }
    
    /*
     * 
     * @author guru
     */
    public function testGetUserById() {
        $user = $this->userDao->getUserById(2);
        $this->assertTrue($user instanceof User);
        $this->assertEquals('Chanaka',  $user->getFirstName());
    }


    /*
     * 
     * @author Guru
     */
    public function testIsUserActive() {
        $this->assertTrue($this->userDao->isUserActive(2));
    }
}

?>
