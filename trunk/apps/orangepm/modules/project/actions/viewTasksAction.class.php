<?php

class viewTasksAction extends sfAction {

    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->storyDao = new StoryDao();
        $this->projectService =  new ProjectService();
        $this->taskService =  new TaskService();
    }
    public function execute($request) {
        $this->storyId = $request->getParameter('storyId');        
        $this->story = $this->storyDao->getStoryById($this->storyId);
        
        if($this->story != NULL) {
            $this->project = $this->projectService->getProjectById($this->story->getProjectId()); 
            $this->projectsId = $this->project->getId();
            $loggedUserObject = null;
            $auth = new AuthenticationService();
            $projectAccessLevel = $auth->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $this->story->getProjectId());
            if ($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN || $projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_MEMBER) {
                $this->taskList = $this->taskService->getTaskByStoryId($this->storyId);
                if (count($this->taskList) == 0) {
                    $this->noRecordMessage = __("No Matching Tasks Found");
                }
            } else {
                $this->redirect("project/viewProjects");
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }
}