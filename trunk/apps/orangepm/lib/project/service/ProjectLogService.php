<?php

/**
 * Service class for Project Log
 */
class ProjectLogService {

    private $projectLogDao = null;
    
    function __construct() {
        $this->projectLogDao = new ProjectLogDao();
    }

    /**
     * Set ProjectLog dao
     * @param ProjectLogDao $ProjectLogDao
     */
    public function setProjectLogDao(ProjectLogDao $projectLogDao) {
        $this->projectLogDao =  $projectLogDao;
    }

    /**
     * Get ProjectLog dao
     * @return ProjectLogDao
     */
    public function getProjectLogDao() {
        return $this->projectLogDao;
    }

    /**
     * Save ProjectLog item
     * @param ProjectLog $ProjectLog
     */
    public function saveLogItem(ProjectLog $projectLog) {
        $this->projectLogDao->saveLogItem($projectLog);
    }

    /**
     * Get ProjectLog item list by project id
     * @param Integer $pojectId
     * @return Collection
     */
    public function getLogItemListByProjectId($pojectId) {
        return $this->projectLogDao->getLogItemListByProjectId($pojectId);
    }

    /**
     * Get ProjectLog item by id
     * @param Integer $logId
     * @return Collection 
     */
    public function getLogItemById($logId) {
        return $this->projectLogDao->getLogItemById($logId);
    }

    /**
     * Delete ProjectLog item
     * @param Integer $logId
     * @return Integer deleted row count
     */
    public function deleteLogItem($logId) {
        return $this->projectLogDao->deleteLogItem($logId);
    }

    /**
     * Update ProjectLog item
     * @param ProjectLog $projectLog
     * @return Integer updated row count 
     */
    public function updateLogItem(ProjectLog $projectLog) {
        return $this->projectLogDao->updateLogItem($projectLog);
    }
    
    /**
     * Get the User Name
     * @param integer $userId
     * @return String Name
     */
    public function getUserName($userId) {
        $userDao = new UserDao();
        $user= $userDao->getUserById($userId);
        return $user->getFirstName().' '.$user->getLastName();
    }
}