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
    
    
    public function testSaveTaskWithNullStoryEnd(){   
        
//        
//         TestDataService::truncateTables(array('User','Project','Story','TaskStatus','Task'));
//        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/Task.yml');
//        TestDataService::truncateTables(array('Task'));
//        
//        
//        $taskList = TestDataService::loadObjectList('Task', $this->fixture, 'Task');
//        $taskDao = $this->getMock('TaskDao');
//        $taskDao->expects($this->any())
//            ->method('saveTask')
//            ->with($this->onConsecutiveCalls($taskList[0] ,$taskList[1]);
//            
        
        
        
        
//        TestDataService::truncateTables(array('User','Project','Story','TaskStatus','Task'));
//        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/Task.yml');
        
        $task = new Task();
        $task->setName("Test name");
        $task->setStoryId(5);
        $task->setEffort(10);
        $task->setDescription("Test description");
        $task->setEstimatedEndDate('2011-01-10');
        $task->setOwnedBy("Dasun");
        $task->setStatus(1);
        
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->any())
            ->method('saveTask')
            ->with($task);
        $this->taskService->setTaskDao($taskDao);
        
       $this->taskService->saveTask($task) ;
        
        
        
    }
}