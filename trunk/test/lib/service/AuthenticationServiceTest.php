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
        TestDataService::truncateTables(array('ProjectUser' ,'User','Project'));
        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/Authentication.yml';
        TestDataService::populate($this->fixture);
        $this->authenticationService= new AuthenticationService();
        
        
    }
    
    
    /**
     *@author Samith
     * @group admin 
     */
    public function testProjectAccessLevelResultValues(){
        
         $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'ProjectUser');

        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->any())
            ->method('getProjectUsersByProjectAndUser')
            ->with()
            ->will($this->onConsecutiveCalls( $projectUserList[1],$projectUserList[3] ,false ));

          $this->authenticationService->setProjectDao($projectDao);
          
          
          
          $userList = TestDataService::loadObjectList('User', $this->fixture, 'User');
         
        $userDao = $this->getMock('UserDao');
        $userDao->expects($this->any())
            ->method('getUserById')
            ->with()
            ->will($this->onConsecutiveCalls($userList[0] , $userList[4],$userList[0],$userList[1] ,$userList[2]));
        
        $this->assertEquals(User::USER_TYPE_SUPER_ADMIN , $this->authenticationService->projectAccessLevel(1,1));
        $this->assertEquals(User::USER_TYPE_PROJECT_ADMIN ,$this->authenticationService->projectAccessLevel(5,1));
        $this->assertEquals(User::USER_TYPE_SUPER_ADMIN ,$this->authenticationService->projectAccessLevel(1,2));        
        $this->assertEquals(User::USER_TYPE_PROJECT_MEMBER ,$this->authenticationService->projectAccessLevel(2,2));
        $this->assertEquals(User::USER_TYPE_UNSPECIFIED ,$this->authenticationService->projectAccessLevel(2,10));
        
        
    }
    
    
    
    
    
}

?>
