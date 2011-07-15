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

}