<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class TaskDaoTest extends PHPUnit_Framework_TestCase {
    
    private $taskDao = null;
    
    protected function setUp() {
        $this->taskDao = new TaskDao();
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/Task.yml');
    }
    
    public function testSaveTask() {
        $task = new Task();
        $task->setName("Test task");
        $task->setStoryId(1);
        $task->setEffort(10);
        $task->setDescription("Test description");
        $task->setOwnedBy("Dasun");
        $task->setStatus(1);
        $this->taskDao->saveTask($task);
        $result = TestDataService::fetchObject("Task", $task->getId());
        $this->assertEquals("Test task", $result->getName());
    }
    
    public function testGetTaskById() {
        $result = $this->taskDao->getTaskById(1);
        $this->assertTrue($result instanceof Task);
        $this->assertEquals(1, $result->getStatus());
    }
    
    public function testGetTaskByStoryId() {
        $results = $this->taskDao->getTaskByStoryId(1);
        $this->assertTrue($results instanceof Doctrine_Collection);
        foreach ($results as $result) {
            $this->assertEquals(1, $result->getStoryId());
        }
    }
    
    /**
     * @group updao
     */
    public function testUpdateTask() {
        $task = new Task();
        $task->setId(1);
        $task->setName('Name 1');
        $task->setStatus(1);
        $task->setEffort(20);
        $task->setOwnedBy("Test");
        $task->setDescription('Description 1');
        $updateCount= $this->taskDao->updateTask($task);
        $this->assertEquals(1, $updateCount);
        $result = TestDataService::fetchObject('Task', 1);
        $this->assertEquals($task->getDescription(), $result->getDescription());
        
        
        
        $task = new Task();
        $task->setId(1);
        $task->setName('Name 1');
        $task->setStatus(1);
        $task->setEffort(20);
        $task->setOwnedBy("Test");
        $task->setEstimatedEndDate('2010-01-10');
        $task->setDescription('Description 1');
        $updateCount= $this->taskDao->updateTask($task);
        $this->assertEquals(1, $updateCount);
        $result = TestDataService::fetchObject('Task', 1);
        $this->assertEquals('2010-01-10', $result->getEstimatedEndDate());
        
        
        $task = new Task();
        $task->setId(1);
        $task->setName('Name 2');
        $task->setStatus(1);
        $task->setEffort(20);
        $task->setOwnedBy("Test");        
        $task->setDescription('Description 1');
        $updateCount= $this->taskDao->updateTask($task);
        $this->assertEquals(1, $updateCount);
        $result = TestDataService::fetchObject('Task', 1);
        $this->assertEquals('2010-01-10', $result->getEstimatedEndDate());
        $this->assertEquals('Name 2', $result->getName());
        
        
        
        
    }
    
    public function testDeleteTask() {
        $deleteCount = $this->taskDao->deleteTask(1);
        $this->assertEquals(1, $deleteCount);
        $result = TestDataService::fetchObject('Task', 1);
        $this->assertFalse($result instanceof Task);
    }
    
    public function testGetTaskTotalEffortByStoryId() {
        $result = $this->taskDao->getTaskTotalEffortByStoryId(1);
        $this->assertEquals(30, $result->getTotalEffort());
    }
    
    public function testGetTaskTotalEffortByStoryIdWithNullId() {
        $result = $this->taskDao->getTaskTotalEffortByStoryId(NUll);
        $this->assertNull($result->getTotalEffort());
    }
    
    /*
     * @author Eranga
     * Testing for getting max estimated ending date from all the tasks for given story
     */
    public function testGetMaxEndingDateOfTasks(){
        $storyId=1;
        $expected='2011-03-24';
        $absolute = $this->taskDao->getMaxEndingDateOfTasks($storyId);
        $this->assertEquals($expected,$absolute);
    }
    
    /*
     * @author Eranga
     * Testing for getting max estimated ending date from all the tasks for non existing story
     */
    public function testGetMaxEndingDateOfTasksForNonExistingStory(){
        $storyId=100;
        $absolute = $this->taskDao->getMaxEndingDateOfTasks($storyId);
        $this->assertNull($absolute);
    }
    
    /*
     * @author Eranga
     * Testing for getting max estimated ending date for story when no tasks defined for that
     */
    public function testGetMaxEndingDateOfTasksForNonExistingTasks(){
        $storyId=3;
        $absolute = $this->taskDao->getMaxEndingDateOfTasks($storyId);
        $this->assertNull($absolute);
    }
}