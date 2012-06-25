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
        $ProjectDao->expects($this->any())
                   ->method('getProjectsByUser')
                   ->with(2)
                   ->will($this->returnValue($projectList));

        $this->projectService->setProjectDao($ProjectDao);

        $returnedProjectList = $this->projectService->getProjectsByUser(2);

        $this->assertEquals(3, count($returnedProjectList));
        
        foreach ($returnedProjectList as $returnedProject) {
            $this->assertTrue($returnedProject instanceof Project);
        }
        $ProjectDao2 = $this->getMock('ProjectDao');
        $ProjectDao2->expects($this->any())
                   ->method('getProjectsByUser')
                   ->with(2 , 100 , true)
                   ->will($this->returnValue(true));
        
        $this->projectService->setProjectDao($ProjectDao2);
        $this->assertTrue($this->projectService->getProjectsByUser(2 , 100 , true));
        
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
    
    
    public function testGetUsersForProjectAsArrayOnlyName() {
        $projectUsersList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'setGetProjectUsersDetails');
        $ProjectDao = $this->getMock('ProjectDao', array('getProjectUsersByProjectId'));
        $ProjectDao->expects($this->once())
                   ->method('getProjectUsersByProjectId')
                   ->will($this->returnValue(null));
        $this->projectService->setProjectDao($ProjectDao);
        $returnedUserList=$this->projectService->getUsersForProjectAsArrayOnlyName(0);
        $this->assertEquals(0, count($returnedUserList));    
    }
    
    
    
    
    /**
     * @author Samith
     *@group buddy
     */
    public function testGetUserTypeTestResultValues(){
        
        //$this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/ProjectService.yml';
         $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'ProjectUser');

        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('getProjectUsersByProjectAndUser')
            ->with(1)
            ->will($this->returnValue($projectUserList[2]));

          $this->projectService->setProjectDao($projectDao);

            $this->assertEquals(1,$this->projectService->getProjectUserType(1,2));
            
            
           
       
    }
    
    
    /**
     *@author Samith
     * @group samith
     */
    public function testGetUserTypeTestWrongInputs(){
         $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('getProjectUsersByProjectAndUser')
            ->with()
            ->will($this->returnValue(false));

          $this->projectService->setProjectDao($projectDao);
         

          $this->assertNull($this->projectService->getProjectUserType(55,6));
    }
    
    /**
     *@author Eranga
     * Testing save project users
     */
    public function testSaveProjectUser(){
         $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('getProjectUsersByProjectAndUser')
            ->with()
            ->will($this->returnValue(false));

          $this->projectService->setProjectDao($projectDao);
         

          $this->assertNull($this->projectService->getProjectUserType(55,6));
    }
    
    /**
     * 
     * @author guru 
     */
    public function testGetProjectsByUserIdAndStatusId() {
        $projectUserList = TestDataService::loadObjectList('ProjectUser', $this->fixture, 'SetProjectUser');

        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('getProjectUsersByUser')
            ->with(1)
            ->will($this->returnValue($projectUserList));

          $this->projectService->setProjectDao($projectDao);
          $result = $this->projectService->getProjectsByUserIdAndStatusId(1,  Project::PROJECT_STATUS_ALL_ID);
          $this->assertEquals(4,  count($result));
          foreach ($result as $project){
              $this->assertTrue($project instanceof Project);
          }          
    }
    
    /*
     * @author eranga
     * Testing weather returning actual number of stories for a given project
     */
    public function testGetRelatedProjectStories(){
        $projectStoryList = TestDataService::loadObjectList('Story', $this->fixture, 'set3');        
        $active=true;
        $projectId=1;
        $pageNo=1;
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->once())
            ->method('getRelatedProjectStories')
            ->with($active,$projectId,$pageNo)
            ->will($this->returnValue($projectStoryList));
        $this->projectService->setStoryDao($storyDao);
        $absolute=$this->projectService->getRelatedProjectStories($active,$projectId,$pageNo);
        $this->assertEquals(4, count($absolute));
        
    }
}
