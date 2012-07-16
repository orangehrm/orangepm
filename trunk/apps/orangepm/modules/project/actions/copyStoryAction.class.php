<?php
/**
 * save tasks in the task table with new storyId
 * @author senura 
 */
class CopyStoryAction extends sfAction {
     
        private $story;
        private $taskList;
        private $newStoryId;
        private $storyId;
        
    public function execute($request) {
        
        $loggedUserId = $this->getUser()->getAttribute($this->loggedUserObject)->getId();       
        $this->copyForm = new CopyStoryForm(array(), array('projectId' => $this->projectId, 'loggedUserId' => $loggedUserId));
        $this->story = new StoryDao();
        
    if ($request->isMethod('post')) {

            $this->copyForm->bind($request->getParameter($this->copyForm->getName()));
            
            if ($this->copyForm->isValid()) {
                
                $this->taskDao =  new TaskDao();
                $dao = new StoryDao();
                $currProjectId = $this->copyForm->getValue('projectId');
                $projectId = $this->copyForm->getValue('project');
                $this->storyId = $this->copyForm->getValue('storyId');
                $story = $this->story->getStoryById($this->storyId);
                $dao = new StoryDao();
               
                
                $inputParameters = array(
                        'name' =>$story->getName(), 
                        'added date' => date('Y-m-d'),
                        'estimated effort' => $story->getEstimation(),
                        'project id' => $projectId,
                        'status' => 'Backlog',
                        'accepted date' => Null
                    );
                
            }
            
            $this->newStoryId =  $dao->saveStory($inputParameters)->getId();
            $this->_saveTasks();
            $this->getUser()->setFlash('addStory', __('The Story was copied successfully'));
            $this->redirect("project/viewStories?" . http_build_query(array('id' => $currProjectId, 'projectName' => 'project2')));
           
            
    }
        
        
    }
    
    /*
     * Copy tasks under same story
     */
    
    private function _saveTasks() {
         
         $this->taskService =  new TaskDao();
         $this->taskList = $this->taskService->getTaskByStoryId($this->storyId);
         
         foreach ($this->taskList as $oldtask) {
             
                if($oldtask != Null) {
                    
                    $task = new Task();
                    $task->setName($oldtask->getName());
                    $task->setEffort($oldtask->getEffort() ? $oldtask->getEffort() : null);
                    $task->setEstimatedEndDate(null);
                    $task->setStatus(1);
                    $task->setOwnedBy($oldtask->getOwnedBy());
                    $task->setDescription($oldtask->getDescription());          
                    $task->setStoryId($this->newStoryId);
                    $this->taskDao->saveTask($task);
                
                }
                     
            }
            
      } 

}
 
 ?>