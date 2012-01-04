<?php
class viewProjectDetailsAction extends sfAction {
    
    protected $projectService = null;
    protected $projectLogService = null;
    
    public function preExecute() {
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
            $this->userName = $this->getUserName($this->userId);
            $this->project = $this->projectService->getProjectById($projectId);
            $this->projectId = $projectId;
            $this->projectForm->setDefault('projectAdmin', array('choices' => $this->project->getUserId()));
            $this->projectForm->setDefault('status', array('choices' => $this->project->getProjectStatusId()));
            $this->projectForm->setDefault('description', $this->project->getDescription());
            
            $viewStoriesDao = new StoryDao();
            $this->storyList = $viewStoriesDao->getRelatedProjectStories(true, $projectId , 1);
            $this->statusCountArray = $this->getPercentageList($projectId);
            $this->projectLogList = $this->projectLogService->getLogItemListByProjectId($this->projectId);
        } else {die;}
    }
    
    public function getUserName($userId) {
        $userDao = new UserDao();
        $user= $userDao->getUserById($userId);
        return $user->getFirstName().' '.$user->getLastName();
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
        $statusCountArray = array('Design' => 0, 'Development' => 0, 'Development Completed' => 0, 'Testing' => 0, 'Rework' => 0, 'Accepted' => 0);
        
        if(count($storyList) != 0) {
            $storyEstimationCount = 0;
            foreach ($storyList->getResults() as $story) {
                $storyEstimationCount+=$story->getEstimation(); 
                $key = $story->getStatus();
                $statusCountArray["$key"]+= $story->getEstimation();
            }
            foreach($statusCountArray as $key => $value) {
                $statusCountArray["$key"] = round(($value/$storyEstimationCount)*100);
            }
        }
        return $statusCountArray;
    }
}