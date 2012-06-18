<?php

class addTaskAction extends sfAction {

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
        $this->project = $this->projectService->getProjectById($this->story->getProjectId());
        $loggedUserObject = null;
        $auth = new AuthenticationService();
        $projectAccessLevel = $auth->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $this->story->getProjectId());
        if ( $projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN || $projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_MEMBER) {
            $this->taskForm = new TaskForm();
            $this->taskList = $this->taskService->getTaskByStoryId($this->storyId);
            if ($request->isMethod('post')) {
                $this->taskForm->bind($request->getParameter('task'));
                if ($this->taskForm->isValid()) {
                    $this->saveTask();
                    echo $this->taskForm->getValue('status');
                    $this->redirect("project/viewTasks?storyId={$this->storyId}");
                }
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }
    
    public function saveTask() {
        $task = new Task();
        $task->setName($this->taskForm->getValue('name'));
        $task->setEffort($this->taskForm->getValue('effort') ? $this->taskForm->getValue('effort') : null);
        $task->setStatus($this->taskForm->getValue('status'));
        $task->setOwnedBy($this->taskForm->getValue('ownedBy'));
        $task->setDescription($this->taskForm->getValue('description'));
        $task->setStoryId($this->storyId);
        $this->taskService->saveTask($task);
    }
}