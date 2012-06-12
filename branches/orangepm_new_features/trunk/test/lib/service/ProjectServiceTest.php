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

    public function testGetProjectsByUser() {

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
    
    
    /**
     * @author Eranga
     * Testing weather user is getting saved when passed through service layer
     */
    public function testSaveProjectForService(){
        $project = new Project();
        $project->setId(11);
        $project->setName('aaa');
        $project->setUserId(2);
        $project->setStartDate('2011-01-01');
        $projectuser=new ProjectUser();
        $collection=new Doctrine_Collection('ProjectUser');
        $projectuser->setProjectId(11);
        $projectuser->setUserId(2);        
        $projectuser->setUserType(1);
        $collection->add($projectuser);
        $project->setProjectUser($collection);
        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('saveProject')
            ->with($project);
        $this->projectService->setProjectDao($projectDao);
        $this->projectService->saveProject($project);   
    }
    
    /**
     * @author Eranga
     * Testing weather user is getting updated when passed through service layer
     */
    public function testUpdateProjectForService(){
        $project = new Project();
        $project->setId(11);
        $project->setName('aaa');
        $project->setUserId(2);
        $project->setStartDate('2011-01-01');
        $projectuser=new ProjectUser();
        $collection=new Doctrine_Collection('ProjectUser');
        $projectuser->setProjectId(11);
        $projectuser->setUserId(2);        
        $projectuser->setUserType(1);
        $collection->add($projectuser);
        $project->setProjectUser($collection);
        $projectDao = $this->getMock('ProjectDao');
        $projectDao->expects($this->once())
            ->method('updateProject')
            ->with($project);
        $this->projectService->setProjectDao($projectDao);
        $this->projectService->updateProject($project);   
    }
    
    /**
     * @author Eranga
     * Testing weather users can be fetched from ProjectUser table for a given user
     */
    public function testGetProjectUsersByUser() {
        $ProjectDao = $this->getMock('ProjectDao', array('getProjectUsersByUser'));
        $ProjectDao->expects($this->once())
                   ->method('getProjectUsersByUser')
                   ->with(1)
                   ->will($this->returnValue(array(new ProjectUser())));
        $this->projectService->setProjectDao($ProjectDao);

        $returnedProjectList = $this->projectService->getProjectUsersByUser(1);
        $this->assertEquals(1, count($returnedProjectList));
     
    }

}