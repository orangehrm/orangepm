<?php

require_once 'PHPUnit/Framework.php';
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectServiceTest extends PHPUnit_Framework_TestCase {

    protected $fixture;
    protected $projectService;

    public function setup() {

        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/ProjectService.yml';
        $this->projectService = new ProjectService();
    }

    public function testGetProjectsByUserService() {

        $projectList = TestDataService::loadObjectList('Project', $this->fixture, 'set1');

        $ProjectDao = $this->getMock('ProjectDao', array('getProjectsByUser'));
        $ProjectDao->expects($this->once())
                   ->method('getProjectsByUser')
                   ->with(2)
                   ->will($this->returnValue($projectList));

        $this->projectService->setProjectDao($ProjectDao);

        $returnedProjectList = $this->projectService->getProjectsByUser(2);

        $this->assertEquals(3, count($returnedProjectList));
        
        foreach ($returnedProjectList as $returnedProject) {
            $this->assertTrue($returnedProject instanceof Project);
        }
    }
    
    /*
     * @author Guru
     */
    public function testGetProjectUsersByProjectIdService() {
        $projectUsersList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'setGetProjectUsersDetails');
        $ProjectDao = $this->getMock('ProjectDao', array('getProjectUsersByProjectId'));
        $ProjectDao->expects($this->once())
                   ->method('getProjectUsersByProjectId')
                   ->will($this->returnValue($projectUsersList));
        $this->projectService->setProjectDao($ProjectDao);
        $returnedProjectUserList = $this->projectService->getProjectUsersByProjectId(3);
        $this->assertEquals(4, count($returnedProjectUserList));
        foreach ($returnedProjectUserList as $returnedProjectUser) {
            $this->assertTrue($returnedProjectUser instanceof ProjectUser);
        }
    }
    
    public function testGetUsersForProjectAsArray() {
        $projectUsersList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'setGetProjectUsersDetails');
        $ProjectDao = $this->getMock('ProjectDao', array('getProjectUsersByProjectId'));
        $ProjectDao->expects($this->once())
                   ->method('getProjectUsersByProjectId')
                   ->will($this->returnValue($projectUsersList));
        $this->projectService->setProjectDao($ProjectDao);
        $returnedUserList=$this->projectService->getUsersForProjectAsArray(3);
        $this->assertEquals(4, count($returnedUserList));
        $this->assertEquals('Thilina', $returnedUserList[0]->getFirstName());     
    }
    
    
    
    
    /**
     *@group blaa
     */
//    public function testGetUserTypeTestResultValues(){
//        
//        //$this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/ProjectService.yml';
//         $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'ProjectUser');
//
//        $projectDao = $this->getMock('ProjectDao');
//        $projectDao->expects($this->once())
//            ->method('getProjectUsersByProjectAndUser')
//            ->with(1)
//            ->will($this->returnValue($projectUserList[2]));
//
//          $this->projectService->setProjectDao($projectDao);
//
//            $this->assertTrue(1,$this->projectService->getProjectUserType(1,2));
//       
//    }

}
