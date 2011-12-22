<?php
class deleteLogAction extends sfAction {
    
    public function execute($request) {
        $projectService = new ProjectService();
        $projectId = $request->getParameter('projectId');
        if ($projectService->isActionAllowedForUser($this->getUser()->getAttribute($loggedUserObject)->getId(), $projectId)) {
            $logId = $request->getParameter('logId');
            $projectName = $request->getParameter('projectName');
            $logService = new ProjectLogService();
            $logService->deleteLogItem($logId);
        }
        if($request->getParameter('from') == 'viewDetails') {
            $this->redirect("project/viewProjectDetails?projectId=$projectId&projectName=$projectName#log");
        } else {
            $this->redirect("project/addLog?projectId=$projectId&projectName=$projectName");
        }
        die;
    }
}