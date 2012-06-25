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
        $auth = new AuthenticationService();
        $projectAccessLevel = $auth->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId);
        if ($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN || $projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_MEMBER) {
                   $id = $request->getParameter('id');
            $storyId = $request->getParameter('storyId');
            $taskService = new TaskService();
            $taskService->deleteTask($id);
            $this->redirect("project/viewTasks?storyId=$storyId");
        }else {
            $storyId = $request->getParameter('storyId');            
            $this->redirect("project/viewTasks?storyId=$storyId");
        }
    }
}