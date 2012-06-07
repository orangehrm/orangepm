<?php

class deleteTaskAction extends sfAction {
    
    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
    }
    
    public function execute($request) {
        $projectService = new ProjectService();
        $projectId = $request->getParameter('projectId');
        if ($projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            $id = $request->getParameter('id');
            $storyId = $request->getParameter('storyId');
            $taskService = new TaskService();
            $taskService->deleteTask($id);
            $this->redirect("project/viewTasks?storyId=$storyId");
        }
        die;
    }
}