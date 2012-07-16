<?php

require_once 'PHPUnit/Framework.php';
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class StoryServiceTest extends PHPUnit_Framework_TestCase {
    
    protected $fixture = null;
    protected $storyService;
    
    public function setUp() {
        
        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/Task.yml';
        $this->storyService = new storyService();
    }
    
    /*
     * @author senura
     * test for testGetProjectList
     */
    
    public function testGetProjectList() {
        
        $projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
        $project = new Project();
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->once())
                 ->method('getProjectList')
                 ->will($this->returnValue($projectList));
        
        $this->storyService->setStoryDao($storyDao);
        $projects = $this->storyService->getProjectList();
        
        foreach ($projects as $project) {
            $this->assertTrue($project instanceof Project);           
        }

        $this->assertEquals(3,count($projects));
        $this->assertEquals('Alfresco-phase1-CR2', $projects[0]->getName());
        
    }
    
    /*
     * @author senura
     * test for testMoveStory
     */
   
   public function testMoveStory() {
       
        $projectList = TestDataService::loadObjectList('Story', $this->fixture, 'Story');
        $result = new Story();
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->once())
                 ->method('moveStory')
                 ->with(1,6)
                 ->will($this->returnValue($projectList[0]));
        
        $this->storyService->setStoryDao($storyDao); 
        $result = $this->storyService->moveStory(1,6);
        $this->assertEquals(1,$result->getId());   
     
   }
   
   /*
    * @author senura
    * test for testGetProjectByUserType
    */
   
   public function testGetProjectByUserType() {
            
        $projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->once())
                 ->method('getProjectByUserType')
                 ->with(1)
                 ->will($this->returnValue($projectList[0]));
        
        $this->storyService->setStoryDao($storyDao);
        $result = $this->storyService->getProjectByUserType(1);
        $this->assertEquals($projectList[0]['name'], $result['name']);   
   }
        
}