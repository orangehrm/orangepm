<?php

class addLogAction extends sfAction {
    
    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->projectLogService = new ProjectLogService();
    }
    
    public function execute($request) {
        if ($request->isMethod('post')) {
            $projectLog =  new ProjectLog();
            $projectLog->setProjectId($request->getParameter('projectId'));
            $projectLog->setAddedBy($request->getParameter('addedBy'));
            $projectLog->setDescription($request->getParameter('description'));
            $projectLog->setLoggedDate($request->getParameter('loggedDate'));
            $this->projectLogService->saveLogItem($projectLog);
        }
        $this->projectId = $request->getParameter('projectId');
        $this->projectName = $request->getParameter('projectName');
        $loggedUserObject = null;
        $this->userId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $this->userName = $this->projectLogService->getUserName($this->userId);
        $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $auth = new AuthenticationService();
        $accessLevel = $auth->projectAccessLevel($loggedUserId, $this->projectId);
        if($accessLevel != User::USER_TYPE_UNSPECIFIED){
            $this->projectLogList = $this->projectLogService->getLogItemListByProjectId($this->projectId);
            if (count($this->projectLogList) == 0) {
                $this->noRecordMessage = __("No Matching Log Items Found");
            }
        }
    }
    
}