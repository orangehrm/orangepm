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
    }
    
    public function execute($request) {
        
        $isSuperAdmin = false;
        if ($this->getUser()->hasCredential('superAdmin')) {
            $isSuperAdmin = true;
        }
        $projectId = $request->getParameter('projectId');
        $projectUserString=$request->getParameter('aaa');
        $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin,'newproject'=>false,'projectid'=>$projectId));
        $loggedUserObject = null;
        if ($this->projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            if ($request->isMethod('post') && ($request->getParameter("saveButton") == __("Save"))) {
                $this->projectForm->bind($request->getParameter($this->projectForm->getName()));
                if($this->projectForm->isValid()){
                    $this->updateProject($projectId,$projectUserString);
                     
                    $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin,'newproject'=>false,'projectid'=>$projectId));
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

    public function updateProject($projectId,$projectUserString) {
        $project = new Project();
        $projectDao = new ProjectDao();
        $projectSevice = new ProjectService();
        
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
        $projectUsersColl=new Doctrine_Collection('ProjectUser');
        //die($projectUserString);
        if($projectUserString!=''){
            $projectUserValues=explode(',', $projectUserString);
            //die($request->getParameter('aaa'));
            foreach($projectUserValues as $single){
                $projectUser=new ProjectUser();
                $projectUser->setUserId($single);
                $projectUser->setProjectId($projectId);
                $projectUser->setUserType(User::USER_TYPE_PROJECT_MEMBER);
                $projectUsersColl->add($projectUser);

            }
                            
                $projectSevice->updateProject($project,$projectUsersColl);
                
            }
            else{
                $projectSevice->updateProject($project);
            }
}
        
        //$project->setProjectUser($projectUsersColl);        
    
    
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