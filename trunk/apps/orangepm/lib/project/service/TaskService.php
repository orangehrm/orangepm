<?php

/**
 * Service class for Task
 */
class TaskService {
    
    private $taskDao = null;  
    private $storyDao = null;
    
    public function __construct() {
        $this->taskDao = new TaskDao();        
        $this->storyDao = new StoryDao();
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
     *Get StoryDao
     * @return StroyDao $storyDao
     */
    public function getStoryDao() {
        return $this->storyDao;
    }
    
    /**
     * Set StroyDao
     * @param StroyDao $storyDao
     */
    public function setStoryDao(StoryDao $storyDao) {
        $this->storyDao = $storyDao;
    }
    
    /**
     * Save task
     * @param Task $task
     */
    public function saveTask(Task $task) {
        $this->taskDao->saveTask($task);
        $taskEndDate = $task->getEstimatedEndDate();
        $storyEndDate = $task->getStory()->getEstimatedEndDate();
        $newEndDate = $taskEndDate;
        if($taskEndDate != null){
            
            if($storyEndDate != null){
                if($taskEndDate < $storyEndDate){
                    $newEndDate = $storyEndDate;
                }
            }
            $this->storyDao->updateEstimatedEndDate($task->getStoryId(), $newEndDate);
        }
       
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
        $taskId = $task->getId();
        $success =  $this->taskDao->updateTask($task); 
        
        $updatedTask = $this->taskDao->getTaskById($taskId);
        $storyId =$updatedTask->getStoryId();        
        $taskMaxEndDate = $this->taskDao->getMaxEndingDateOfTasks($storyId);        
        $this->storyDao->updateEstimatedEndDate($storyId, $taskMaxEndDate);
            
        
        return success;
    }
    
    /**
     * Delete Task
     * @param integer $id
     * @return Deleted record count
     */
    public function deleteTask($id) {        
        $updatedTask = $this->taskDao->getTaskById($id);
        $storyId =$updatedTask->getStoryId();
        $success =  $this->taskDao->deleteTask($id);
        
        $taskMaxEndDate = $this->taskDao->getMaxEndingDateOfTasks($storyId);        
        $this->storyDao->updateEstimatedEndDate($storyId, $taskMaxEndDate);           
        
        return success;
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
    
    public function getTaskTotalEffortByStoryId($storyId) {
       $taskTotalObject = $this->taskDao->getTaskTotalEffortByStoryId($storyId);
       return $taskTotalObject->getTotalEffort();
    }
}