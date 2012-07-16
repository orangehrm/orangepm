<?php

class MoveStoryAction extends sfAction {
     
    
    public function execute($request) {
             
        $loggedUserId = $this->getUser()->getAttribute($this->loggedUserObject)->getId();       
        $this->moveForm = new MoveStoryForm(array(), array('projectId' => $this->projectId, 'loggedUserId' => $loggedUserId));
               
        if ($request->isMethod('post')) {
            $this->moveForm->bind($request->getParameter($this->moveForm->getName()));          
            
            if ($this->moveForm->isValid()) { 
            
                $this->storyService = new StoryService();
                $currProjectId = $this->moveForm->getValue('projectId');
                $projectId = $this->moveForm->getValue('project');
                $storyId = $this->moveForm->getValue('storyId');
                $this->storyService->moveStory($storyId, $projectId);
                $this->getUser()->setFlash('addStory', __('The Story was moved successfully'));      
                $this->redirect("project/viewStories?" . http_build_query(array('id' => $currProjectId, 'projectName' => 'project2')));

            }
        }
   }
}

?>