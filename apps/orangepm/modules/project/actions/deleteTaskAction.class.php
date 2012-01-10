<?php

class deleteTaskAction extends sfAction {
    
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