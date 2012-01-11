<?php
class viewProjectDetailsAction extends sfAction {

    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->projectService = new ProjectService();
        $this->projectLogService = new ProjectLogService();
    }
    
    public function execute($request) {
        
        $isSuperAdmin = false;
        if ($this->getUser()->hasCredential('superAdmin')) {
            $isSuperAdmin = true;
        }
        $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin));
        $projectId = $request->getParameter('projectId');
        $loggedUserObject = null;
        if ($this->projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            if ($request->isMethod('post') && ($request->getParameter("saveButton") == __("Save"))) {
                $this->projectForm->bind($request->getParameter($this->projectForm->getName()));
                if($this->projectForm->isValid()){
                    $this->updateProject($projectId);
                }
            } 
            $this->userId = $this->getUser()->getAttribute($loggedUserObject)->getId();
            $this->userName = $this->projectLogService->getUserName($this->userId);
            $this->project = $this->projectService->getProjectById($projectId);
            $this->projectId = $projectId;
            $this->projectForm->setDefault('projectAdmin', array('choices' => $this->project->getUserId()));
            $this->projectForm->setDefault('status', array('choices' => $this->project->getProjectStatusId()));
            $this->projectForm->setDefault('description', $this->project->getDescription());
            
            $viewStoriesDao = new StoryDao();
            $this->storyList = $viewStoriesDao->getRelatedProjectStories(true, $projectId , 1);
            if (count($this->storyList) == 0) {
                $this->noStoryMessage = __("No Matching Stories Found");
            }
            $this->statusCountArray = $this->getPercentageList($projectId);
            $this->projectLogList = $this->projectLogService->getLogItemListByProjectId($this->projectId);
            if (count($this->projectLogList) == 0) {
                $this->noLogsMessage = __("No Matching Log Items Found");
            }
        } else {die;}
    }

    public function updateProject($projectId) {
        $project = new Project();
        $projectDao = new ProjectDao();
        
        $project->setId($projectId);
        $project->setName($this->projectForm->getValue('name'));
        $project->setProjectStatusId($this->projectForm->getValue('status'));
        if ($this->projectForm->getValue('projectAdmin') != 0) {
           $project->setUserId($this->projectForm->getValue('projectAdmin'));
        }
        $project->setDescription($this->projectForm->getValue('description'));
        $project->setStartDate($this->projectForm->getValue('startDate'));
        if ($this->projectForm->getValue('endDate') != '') {
            $project->setEndDate($this->projectForm->getValue('endDate'));
        }
        $projectDao->updateProject($project);
    }
    
    public function getPercentageList($projectId) {
        $viewStoriesDao = new StoryDao();
        $storyList = $viewStoriesDao->getRelatedProjectStories(true, $projectId , 1);
        $statusCountArray = array('Backlog' => 0, 'Design' => 0, 'Development' => 0, 'Development Completed' => 0, 'Testing' => 0, 'Rework' => 0, 'Accepted' => 0);
        
        if(count($storyList) != 0) {
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
}