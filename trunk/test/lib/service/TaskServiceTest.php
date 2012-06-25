<?php

require_once 'PHPUnit/Framework.php';
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class TaskServiceTest extends PHPUnit_Framework_TestCase {
    
    protected $fixture = null;
    protected $taskService = null;
    
    protected function setUp() {
        
        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/Task.yml';
        $this->taskService = new TaskService();
    }
    
    public function testSaveTask() {
        $task = new Task();
        $task->setName("Test name");
        $task->setStoryId(1);
        $task->setEffort(10);
        $task->setDescription("Test description");
        $task->setOwnedBy("Dasun");
        $task->setStatus(1);
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('saveTask')
            ->with($task);
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->saveTask($task);
    }
    
    /**
     *@author Samith
     * @group samith 
     */
    public function testSetAndGetStoryDao(){
        
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->any())
            ->method('updateEstimatedEndDate')
            ->with(1,2)
            ->will($this->returnValue(true));
        
        $this->taskService->setStoryDao($storyDao);
        $this->assertTrue($this->taskService->getStoryDao()->updateEstimatedEndDate(1,2));
        
    }
    
    
    public function testGetAndSetTaskDao(){
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('deleteTask')
            ->with(1)
            ->will($this->returnValue('1'));
        
        $this->taskService->setTaskDao($taskDao);
        $this->assertEquals('1',$this->taskService->getTaskDao()->deleteTask(1));
    }
    
    /**
     *@author Samith
     * @group samith 
     */
    public function testSaveTaskWithNoStoryMaxEndDate(){
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $storyList = TestDataService::loadObjectList('Task', $this->fixture, 'Story');
        $taskList[4]->setStory($storyList[5]);
        
        //$taskList[3]->setStory();
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('saveTask')
            ->with($taskList[4]);
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->saveTask($taskList[4]);
        
        
    }
    
    /**
     *@author Samith
     * @group samith 
     */
    public function testSaveTaskWithLowerStoryMaxEndDate(){
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $storyList = TestDataService::loadObjectList('Task', $this->fixture, 'Story');
        $taskList[5]->setStory($storyList[4]);
        
        //$taskList[3]->setStory();
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('saveTask')
            ->with($taskList[5]);
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->saveTask($taskList[5]);
        
        
    }
    
    /**
     *@author Samith
     * @group samith 
     */
    public function testSaveTaskWithHigherStoryMaxEndDate(){
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $storyList = TestDataService::loadObjectList('Task', $this->fixture, 'Story');
        $taskList[6]->setStory($storyList[3]);
        
        //$taskList[3]->setStory();
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('saveTask')
            ->with($taskList[6]);
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->saveTask($taskList[6]);
        
        
    }
    
    public function testGetTaskById() {
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->once())
            ->method('getTaskById')
            ->with(1)
            ->will($this->returnValue($taskList[0]));
        $this->taskService->setTaskDao($taskDao);
        $result = $this->taskService->getTaskById(1);
        $this->assertEquals($taskList[0]['name'], $result['name']);
    }
    
    public function testGetTaskByStoryId() {
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $returnTaskList = array($taskList[0], $taskList[1]);
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->once())
            ->method('getTaskByStoryId')
            ->with(1)
            ->will($this->returnValue($returnTaskList));
        $this->taskService->setTaskDao($taskDao);
        $retuls = $this->taskService->getTaskByStoryId(1);
        $this->assertEquals($taskList[0]['name'], $retuls[0]['name']);
    }
    
    /**
     * @author Samith
     *@group samith 
     */
    public function testUpdateTask() {
        
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $storyList = TestDataService::loadObjectList('Task', $this->fixture, 'Story');
        
        $taskDao = $this->getMock('TaskDao' , array('updateTask','getTaskById','getMaxEndingDateOfTasks'));
        $taskDao->expects($this->any())
            ->method('updateTask')
            ->with($taskList[0])
            ->will($this->returnValue(1));
        
        $taskDao->expects($this->any())
            ->method('getTaskById')
            ->with()
            ->will($this->returnValue($taskList[0]));
        
        $taskDao->expects($this->any())
            ->method('getMaxEndingDateOfTasks')
            ->with()
            ->will($this->returnValue($taskList[0]['estimatedEndDate']));
        
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->any())
            ->method('updateEstimatedEndDate')
            ->with()
            ->will($this->returnValue(true));
        
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->setStoryDao($storyDao);
        $this->assertTrue($this->taskService->updateTask($taskList[0]));
//     
    }
    
    /**
     * @author Samith
     *@group samith 
     */
    public function testDeleteTask() {       
        
        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
        $storyList = TestDataService::loadObjectList('Task', $this->fixture, 'Story');
        
        $taskDao = $this->getMock('TaskDao' , array('deleteTask','getTaskById','getMaxEndingDateOfTasks'));
        $taskDao->expects($this->any())
            ->method('deleteTask')
            ->with($taskList[0])
            ->will($this->returnValue(1));
        
        $taskDao->expects($this->any())
            ->method('getTaskById')
            ->with()
            ->will($this->returnValue($taskList[0]));
        
        $taskDao->expects($this->any())
            ->method('getMaxEndingDateOfTasks')
            ->with()
            ->will($this->returnValue($taskList[0]['estimatedEndDate']));
        
        $storyDao = $this->getMock('StoryDao');
        $storyDao->expects($this->any())
            ->method('updateEstimatedEndDate')
            ->with()
            ->will($this->returnValue(true));
        
        $this->taskService->setTaskDao($taskDao);
        $this->taskService->setStoryDao($storyDao);
        $this->assertTrue($this->taskService->deleteTask($taskList[0]));
    }
    
}