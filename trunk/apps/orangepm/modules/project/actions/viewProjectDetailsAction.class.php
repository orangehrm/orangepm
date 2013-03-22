<?php

class viewProjectDetailsAction extends sfAction {

    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->projectService = new ProjectService();
        $this->projectLogService = new ProjectLogService();
        $this->taskService = new TaskService();
        $this->userService = new UserService();
        $this->storyEstimationCount = 0;
        $this->authenticationService = new AuthenticationService();
        $this->projectAccessLevel = User::USER_TYPE_UNSPECIFIED;
    }

    public function execute($request) {
        $isSuperAdmin = false;
        if ($this->getUser()->hasCredential('superAdmin')) {
            $isSuperAdmin = true;
        }
        $projectId = $request->getParameter('projectId');
        $this->project = $this->projectService->getProjectById($projectId);
        $this->usersyList = $this->projectService->getUsersByProjectId($projectId);
        if ($this->project != NULL) {
            $projectUserString = $request->getParameter('aaa');
            $removeUserId = $this->project->getUserId();
            $loggedUserObject = null;
            $this->userDao = new UserDao();

            $this->columnName = 'date_added';
            $this->order = 'ASC';
            if (($request->getParameter('columnname') != null) && ($request->getParameter('order') != null )) {
                $this->order = $request->getParameter('order');
                $this->columnName = $request->getParameter('columnname');
            }


            $this->projectDetailList = $this->projectService->getProjectStatus($projectId);

            // get project links
            $this->projectLinkList = $this->projectService->getProjectLinkList($projectId);

            $isProjectAccessLevel = $this->authenticationService->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId);
            $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin, 'newproject' => false, 'projectid' => $projectId, 'removeUserId' => $removeUserId));
            $this->projectAccessLevel = User::USER_TYPE_UNSPECIFIED;
            $this->projectAccessLevel = $this->authenticationService->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId);
            if ($this->projectAccessLevel != User::USER_TYPE_UNSPECIFIED) {
                if ($request->isMethod('post') && ($request->getParameter("saveButton") == __("Save"))) {
                    $this->projectForm->bind($request->getParameter($this->projectForm->getName()));
                    if ($this->projectForm->isValid()) {
                        $this->updateProject($projectId, $projectUserString);
                        $this->updatedProject = $this->projectService->getProjectById($projectId);
                        $removeUserId = $this->updatedProject->getUserId();
                        $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin, 'newproject' => false, 'projectid' => $projectId, 'removeUserId' => $removeUserId));
                        $this->projectDetailList = $this->projectDetailList = $this->projectService->getProjectStatus($projectId);
                    }
                }
                $this->userId = $this->getUser()->getAttribute($loggedUserObject)->getId();
                $this->userRole = $this->userDao->getUserById($this->userId)->getUserType();
                $this->userName = $this->projectLogService->getUserName($this->userId);
                $this->projectId = $projectId;
                $this->projectForm->setDefault('projectAdmin', array('choices' => $this->project->getUserId()));
                $this->projectForm->setDefault('status', array('choices' => $this->project->getProjectStatusId()));
                $this->projectForm->setDefault('description', $this->project->getDescription());

                $viewStoriesDao = new StoryDao();
                $this->storyList = $viewStoriesDao->getSortedByColumnName(true, $this->projectId, $pageNo, $this->columnName, $this->order);
                $this->order = $this->__sortByColumnName();
                if (count($this->storyList) == 0) {
                    $this->noStoryMessage = __("No Matching Stories Found");
                }
                $this->statusCountArray = $this->getPercentageList($projectId);
                $this->projectLogList = $this->projectLogService->getLogItemListByProjectId($this->projectId);
                if (count($this->projectLogList) == 0) {
                    $this->noLogsMessage = __("No Matching Log Items Found");
                }
            } else {
                $this->redirect("project/viewProjects");
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    public function updateProject($projectId, $projectUserString) {
        if (($this->projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($this->projectAccessLevel == User::USER_TYPE_SUPER_ADMIN)) {

            $project = new Project();
            $projectDao = new ProjectDao();
            $projectSevice = new ProjectService();

            $loggedUserObject = null;


            $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();

            $project->setId($projectId);
            $project->setName($this->projectForm->getValue('name'));
            $project->setProjectStatusId($this->projectForm->getValue('status'));
            if ($this->projectForm->getValue('projectAdmin') != 0) {
                $project->setUserId($this->projectForm->getValue('projectAdmin'));
            } else {
                $project->setUserId($loggedUserId);
            }
            $project->setDescription($this->projectForm->getValue('description'));
            $project->setStartDate($this->projectForm->getValue('startDate'));
            if ($this->projectForm->getValue('endDate') != '') {
                $project->setEndDate($this->projectForm->getValue('endDate'));
            }
            $project->setTotalEstimatedEffort($this->projectForm->getValue('estimatedTotalEffort'));
            $project->setCurrentEffort($this->projectForm->getValue('currentEffort'));
            $projectUsersColl = new Doctrine_Collection('ProjectUser');
            $projectUser = new ProjectUser();
            $projectUser->setUserId($project->getUserId());
            $projectUser->setProjectId($projectId);
            $projectUser->setUserType(User::USER_TYPE_PROJECT_ADMIN);
            $projectUsersColl->add($projectUser);
            if ($projectUserString != '') {
                $projectUserValues = explode(',', $projectUserString);
                foreach ($projectUserValues as $single) {
                    $projectUser = new ProjectUser();
                    $projectUser->setUserId($single);
                    $projectUser->setProjectId($projectId);
                    $projectUser->setUserType(User::USER_TYPE_PROJECT_MEMBER);
                    $projectUsersColl->add($projectUser);
                }




                $projectSevice->updateProject($project, $projectUsersColl);
            } else {
                $projectSevice->updateProject($project, $projectUsersColl);
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    //$project->setProjectUser($projectUsersColl);        


    public function getPercentageList($projectId) {
        $viewStoriesDao = new StoryDao();
        $storyList = $viewStoriesDao->getSortedByColumnName(true, $projectId, 1, $this->columnName, $this->order);
        $statusCountArray = array('Backlog' => 0, 'Design' => 0, 'Development' => 0, 'Development Completed' => 0, 'Testing' => 0, 'Rework' => 0, 'Accepted' => 0);

        if (count($storyList) != 0) {
            $storyEstimationCount = 0;
            foreach ($storyList->getResults() as $story) {
                $storyEstimationCount+=$story->getEstimation();
                $key = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus();
                $statusCountArray["$key"]+= $story->getEstimation();
            }
            $this->storyEstimationCount = $storyEstimationCount;
        }

        return $statusCountArray;
    }

    private function __sortByColumnName() {
        if ($this->order == "") {
            $this->order = "ASC";
        } elseif ($this->order == "ASC") {
            $this->order = "DESC";
        } elseif ($this->order == "DESC") {
            $this->order = "ASC";
        }
        return $this->order;
    }

}
