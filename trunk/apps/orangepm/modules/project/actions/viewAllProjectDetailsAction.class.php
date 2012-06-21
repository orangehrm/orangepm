<?php

class viewAllProjectDetailsAction extends sfAction {

    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->projectService = new ProjectService();
        $this->projectAccessLevel = User::USER_TYPE_UNSPECIFIED;
    }

    public function execute($request) {
        if (!$this->getUser()->hasCredential('superAdmin')) {
            $this->redirect('project/viewProjects');
        }
        $this->statusId = $request->getParameter('selectedStatusId');
        $loggedUserId = $this->getUser()->getAttribute($this->loggedUserObject)->getId();
        if($this->statusId ==null){
            $this->statusId = Project::PROJECT_STATUS_DEFAULT_ID;
        }
        $this->projects = $this->projectService->getAllProjects(true, $this->statusId);
        $this->projectProgressList=$this->getPercentageList($this->projects);
    }

    public function getPercentageList($projects) {
        $allStatusCountArray = array();
        foreach($projects as $single) {
            $storyList = $this->projectService->getRelatedProjectStories(true, $single->getId(), 1);
            $statusCountArray = array('project'=>$single,'EstCount'=>0,'Backlog' => 0, 'Design' => 0, 'Development' => 0, 'Development Completed' => 0, 'Testing' => 0, 'Rework' => 0, 'Accepted' => 0);
            if (count($storyList) != 0) {
                $storyEstimationCount = 0;
                foreach ($storyList->getResults() as $story) {
                    $storyEstimationCount+=$story->getEstimation();
                    $key = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus();
                    $statusCountArray["$key"]+= $story->getEstimation();
                }
                $statusCountArray['EstCount'] = $storyEstimationCount;
                $allStatusCountArray[]=$statusCountArray;
            }
        }
        return $allStatusCountArray;
    }

}

?>
