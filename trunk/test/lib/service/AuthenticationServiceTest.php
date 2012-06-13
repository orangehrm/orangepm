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
    public function testIsProjectEditbleByUserResultValues(){
        
         $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'ProjectUser');

        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->any())
            ->method('getProjectUsersByProjectAndUser')
            ->with()
            ->will($this->onConsecutiveCalls( $projectUserList[1],$projectUserList[3] ));

          $this->authenticationService->setProjectDao($projectDao);
          
          
          
          $userList = TestDataService::loadObjectList('User', $this->fixture, 'User');
         
        $userDao = $this->getMock('UserDao');
        $userDao->expects($this->any())
            ->method('getUserById')
            ->with()
            ->will($this->onConsecutiveCalls($userList[0] , $userList[3],$userList[0],$userList[1] ));
        
        $this->assertTrue($this->authenticationService->isProjectMetadataEditbleByUser(1,1));
        $this->assertTrue($this->authenticationService->isProjectMetadataEditbleByUser(5,1));
        $this->assertTrue($this->authenticationService->isProjectMetadataEditbleByUser(1,2));        
        $this->assertTrue(!$this->authenticationService->isProjectMetadataEditbleByUser(2,2));
        
        
    }
    
    /**
     *@group admin 
     */
    public function testIsProjectEditbleByUserForAdmin(){
        
         $userList = TestDataService::loadObjectList('User', $this->fixture, 'User');
         
        $userDao = $this->getMock('UserDao');
        $userDao->expects($this->any())
            ->method('getUserById')
            ->with()
            ->will($this->onConsecutiveCalls($userList[0] ));

        $this->authenticationService->setUserDao($userDao);
        $this->assertTrue($this->authenticationService->isProjectMetadataEditbleByUser(1,1));
            
       
        
        
    }
}

?>
