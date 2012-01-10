<?php

class viewTasksAction extends sfAction {

    public function preExecute() {
        $this->storyDao = new StoryDao();
        $this->projectService =  new ProjectService();
        $this->taskService =  new TaskService();
    }
    public function execute($request) {
        $this->storyId = $request->getParameter('storyId');
        $this->story = $this->storyDao->getStoryById($this->storyId);
        $this->project = $this->projectService->getProjectById($this->story->getProjectId());
        $loggedUserObject = null;
        if ($this->projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $this->project->getId())) {
            $this->taskList = $this->taskService->getTaskByStoryId($this->storyId);
            if (count($this->taskList) == 0) {
                $this->noRecordMessage = __("No Matching Tasks Found");
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }
}