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
        $taskDao->expects($this->once())
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
    
    public function testUpdateTask() {
        $task = new Task();
        $task->setId(1);
        $task->setName('New name');
        $task->setEffort(20);
        $task->setDescription('New description');
        $task->setOwnedBy("Dasun");
        $task->setStatus(2);
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->once())
            ->method('updateTask')
            ->with($task)
            ->will($this->returnValue(1));
        $this->taskService->setTaskDao($taskDao);
        $result = $this->taskService->updateTask($task);
        $this->assertEquals(1, $result);
    }
    
    public function testDeleteTask() {
        $taskDao = $this->getMock('TaskDao');
        $taskDao->expects($this->once())
            ->method('deleteTask')
            ->with(1)
            ->will($this->returnValue(1));
        $this->taskService->setTaskDao($taskDao);
        $result = $this->taskService->deleteTask(1);
        $this->assertEquals(1, $result);
    }
}