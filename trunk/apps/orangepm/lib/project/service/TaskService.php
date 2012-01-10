<?php

/**
 * Service class for Task
 */
class TaskService {
    
    private $taskDao = null;
    
    public function __construct() {
        $this->taskDao = new TaskDao();
    }
    
    /**
     * Get TaskDao
     * @return TaskDao $taskDao
     */
    public function getTaskDao() {
        return $this->taskDao;
    }
    
    /**
     * Set TaskDao
     * @param TaskDao $taskDao
     */
    public function setTaskDao(TaskDao $taskDao) {
        $this->taskDao = $taskDao;
    }
    
    /**
     * Save task
     * @param Task $task
     */
    public function saveTask(Task $task) {
        $this->taskDao->saveTask($task);
    }
    
    /**
     * Get task by Id
     * @param integer $id
     * @return Task
     */
    public function getTaskById($id) {
        return $this->taskDao->getTaskById($id);
    }
    
    /**
     * Get All tasks for a Story
     * @param $storyId
     * @return Doctrine_Collection
     */
    public function getTaskByStoryId($storyId) {
        return $this->taskDao->getTaskByStoryId($storyId);
    }
    
    /**
     * Update Task
     * @param Task $task
     * @return update record count
     */
    public function updateTask(Task $task) {
        return $this->taskDao->updateTask($task);
    }
    
    /**
     * Delete Task
     * @param integer $id
     * @return Deleted record count
     */
    public function deleteTask($id) {
        return $this->taskDao->deleteTask($id);
    }
    
    /**
     * Get status forn status list
     * @param $status
     * @return String Status 
     */
    public function getStatus($status) {
        $statusArray = $this->getAllTaskStatusArray();
        return $statusArray[$status];
    }
    
    /**
     * 
     */
    public function getAllTaskStatusArray() {
        $statusCollection = $this->taskDao->getAllTaskStatusArray();
        $statusArray = null;
        foreach ($statusCollection as $status) {
            $statusArray[$status->getId()] = $status->getName();
        }
        return $statusArray;
    }
    
    public function getTaskStatusId($status) {
        $statusCollection = $this->taskDao->getAllTaskStatusArray();
        foreach ($statusCollection as $status) {
            if($status->getName() == $status) {
                return $status->getId();
            }
        }
    }
}