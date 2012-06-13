<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'PHPUnit/Framework.php';
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';


class AuthenticationTest extends PHPUnit_Framework_TestCase {
    
    protected $authenticationService;
    
    protected function setUp() {
        TestDataService::truncateTables(array('ProjectUser'));
        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/Authentication.yml';
        TestDataService::populate($this->fixture);
        $this->authenticationService= new AuthenticationService();
        
        
    }
    
    
    /**
     *@author Samith
     * @group auth 
     */
    public function testIsProjectEditbleByUserResultValues(){
        
         $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'ProjectUser');

        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->any())
            ->method('getProjectUsersByProjectAndUser')
            ->with()
            ->will($this->onConsecutiveCalls($projectUserList[0] , $projectUserList[1],$projectUserList[2] ,$projectUserList[3] ));

          $this->authenticationService->setProjectDao($projectDao);
        
        $this->assertTrue(!$this->authenticationService->isProjectEditbleByUser(1,1));
        $this->assertTrue($this->authenticationService->isProjectEditbleByUser(5,1));
        $this->assertTrue($this->authenticationService->isProjectEditbleByUser(1,2));        
        $this->assertTrue(!$this->authenticationService->isProjectEditbleByUser(2,2));
        
        
    }
}

?>
