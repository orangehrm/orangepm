<?php
class updateLogAction extends sfAction {
    
    public function preExecute() {
        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
    }
    
    public function execute($request) {
        $projectId = $request->getParameter('projectId');
        $projectService = new ProjectService();
        $loggedUserObject = null;
        if ($projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            $logId = $request->getParameter('logId');
            $loggedDate = $request->getParameter('loggedDate');
            $addedBy = $request->getParameter('addedBy');
            $description = $request->getParameter('description');
            $projectLog = new ProjectLog();
            $logService = new ProjectLogService();
            
            $projectLog->setId($logId);
            $projectLog->setDescription($description);
            
            $logService->updateLogItem($projectLog);
        }
        die;
    }
}