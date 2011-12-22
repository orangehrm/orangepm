<?php
class updateLogAction extends sfAction {
    
    public function execute($request) {
        $projectId = $request->getParameter('projectId');
        $projectService = new ProjectService();
        if ($projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            $logId = $request->getParameter('logId');
            $loggedDate = $request->getParameter('loggedDate');
            $addedBy = $request->getParameter('addedBy');
            $description = $request->getParameter('description');
            $projectLog = new ProjectLog();
            $logService = new ProjectLogService();
            
            $projectLog->setId($logId);
            $projectLog->setLoggedDate($loggedDate);
            $projectLog->setAddedBy($addedBy);
            $projectLog->setDescription($description);
            
            $logService->updateLogItem($projectLog);
        }
        die;
    }
}