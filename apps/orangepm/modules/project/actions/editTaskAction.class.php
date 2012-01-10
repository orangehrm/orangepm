<?php

class editTaskAction extends sfAction {

    public function preExecute(){
        $this->taskService = new TaskService();
    }
    public function execute($request) {
        $projectService = new ProjectService();
        $projectId = $request->getParameter('projectId');
        $loggedUserObject = null;
        if ($projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            $task = new Task();
            $task->setId($request->getParameter('id'));
            $task->setName($request->getParameter('name'));
            $task->setEffort($request->getParameter('effort') ? $request->getParameter('effort') : 'NULL');
            $task->setStatus($request->getParameter('status'));
            $task->setDescription($request->getParameter('description'));
            $task->setOwnedBy($request->getParameter('ownedBy'));
            $this->taskService->updateTask($task);
        }
        die;
    }
}